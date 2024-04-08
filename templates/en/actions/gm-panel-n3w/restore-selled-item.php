<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è uno staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

// Verifica se è stato fornito un ID valido
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Invalid ID");
    header("Location: $BackUrl");
    exit();
}

$id = $_GET["id"];

// Trova l'elemento corrispondente all'ID fornito
$query = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE row = ?");
$query->execute([$id]);
$item = $query->fetch(PDO::FETCH_ASSOC);

// Se l'elemento non esiste, mostra un errore
if (!$item) {
    SetErrorAlert("ID does not exist");
    header("Location: $BackUrl");
    exit();
}

$Info = $item["Text2"];
$ItemUID = $item["Value1"];
$ItemID = $item["Value2"];
$count = $item["Value4"];
$Type = floor($ItemID / 1000);
$TypeID = $ItemID % 1000;

$forbiddenItems = [25229, 25230, 25231, 25232, 25233, 94001, 94002, 94003, 94004, 94005, 94006, 100220, 100221];
if (in_array($ItemID, $forbiddenItems)) {
    SetErrorAlert("Restoring forbidden item");
    header("Location: $BackUrl");
    exit();
}

// Verifica se l'utente è attualmente online
$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = ? AND LoginStatus = 1");
$query->execute([$item["UserUID"]]);
if ($query->fetchColumn()) {
    SetErrorAlert("User is currently online. You must kick them before restoring the item.");
    header("Location: $BackUrl");
    exit();
}

// Verifica se l'oggetto è già stato ripristinato
$queries = [
    "SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID = ?",
    "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID = ?",
    "SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID = ?"
];
foreach ($queries as $query) {
    $stmt = $conn->prepare($query);
    $stmt->execute([$ItemUID]);
    if ($stmt->fetchColumn()) {
        SetErrorAlert("Item has already been restored");
        header("Location: $BackUrl");
        exit();
    }
}

// Ottieni le gemme e il nome dell'oggetto artigianale
$GemsStr = trim(substr($Info, 0, strpos($Info, "(")));
$Gems = explode(",", $GemsStr);
$Craftname = substr($Info, strpos($Info, ":") + 1, 20);
if (strlen($Craftname) != 20) {
    $Craftname = "";
}

// Trova uno slot libero nel magazzino
$Slot = 0;
while ($Slot <= 240) {
    $query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = ? AND Slot = ?");
    $query->execute([$item["UserUID"], $Slot]);
    if (!$query->fetchColumn()) {
        break;
    }
    $Slot++;
}
// Se non ci sono slot liberi, mostra un errore
if ($Slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("Location: $BackUrl");
    exit();
}

// Inserisci l'oggetto ripristinato nel database
$query = $conn->prepare("INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID, UserUID, ItemID, ItemUID, Type, TypeID, Slot, Quality, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, Craftname, [Count], Maketime, Maketype, Del)
    VALUES (1, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'X', 0)");
$query->execute([$item["UserUID"], $ItemID, $ItemUID, $Type, $TypeID, $Slot, $Gems[0], $Gems[1], $Gems[2], $Gems[3], $Gems[4], $Gems[5], $Craftname, $count]);

// Registra l'azione nel log
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Restore dropped item', 'ROW: $id; USERUID: $Item[UserUID]; USERID: $Item[UserID]; ITEMUID: $ItemUID; ITEMID: $ItemID', '$UserIP')");
$query->execute();
		
// Incrementa lo slot per mostrare l'informazione all'utente
$Slot++;

SetSuccessAlert("Item successfully restored to slot {$Slot} of the warehouse");
header("Location: $BackUrl");
exit();