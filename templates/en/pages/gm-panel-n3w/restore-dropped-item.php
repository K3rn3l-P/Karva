<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit;
}

// Verifica se l'ID è fornito e se è numerico
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit;
}

$id = $_GET["id"];

// Trova l'elemento danneggiato
$stmt = $pdo->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE row = :id");
$stmt->execute(array(':id' => $id));
$Item = $stmt->fetch(PDO::FETCH_ASSOC);

// Se l'elemento non esiste, mostra un errore
if (!$Item) {
    SetErrorAlert("ID not exists");
    header("Location: $BackUrl");
    exit;
}

$Info = $Item["Text2"];
$ItemUID = $Item["Value1"];
$ItemID = $Item["Value2"];
$Type = floor($ItemID / 1000);
$TypeID = $ItemID % 1000;

// Verifica se l'utente è attualmente in gioco
$stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = :UserUID AND LoginStatus = 1");
$stmt->execute(array(':UserUID' => $Item['UserUID']));
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("Location: $BackUrl");
    exit;
}

// Verifica se l'oggetto è già stato ripristinato
$stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID = :ItemUID UNION 
                      SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID = :ItemUID UNION 
                      SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID = :ItemUID");
$stmt->execute(array(':ItemUID' => $ItemUID));
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    SetErrorAlert("Item already restored");
    header("Location: $BackUrl");
    exit;
}

// Ottieni gemme e nome dell'oggetto
$GemsStr = trim(substr($Info, 0, strpos($Info, "(")));
$Gems = explode(",", $GemsStr);
$Craftname = substr($Info, strpos($Info, ":") + 1, 20);

// Trova lo slot libero
$Slot = 0;
while ($Slot <= 240) {
    $stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = :UserUID AND Slot = :Slot");
    $stmt->execute(array(':UserUID' => $Item['UserUID'], ':Slot' => $Slot));
    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        break;
    }
    $Slot++;
}

// Se non ci sono slot liberi, mostra un errore
if ($Slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("Location: $BackUrl");
    exit;
}

// Inserisci l'oggetto ripristinato
$stmt = $pdo->prepare("INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID, UserUID, ItemID, ItemUID, Type, TypeID, Slot, Quality, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, Craftname, [Count], Maketime, Maketype, Del)
            VALUES (1, :UserUID, :ItemID, :ItemUID, :Type, :TypeID, :Slot, 0, :Gem1, :Gem2, :Gem3, :Gem4, :Gem5, :Gem6, :Craftname, 1, CURRENT_TIMESTAMP, 'X', 0)");
$stmt->execute(array(
    ':UserUID' => $Item['UserUID'],
    ':ItemID' => $ItemID,
    ':ItemUID' => $ItemUID,
    ':Type' => $Type,
    ':TypeID' => $TypeID,
    ':Slot' => $Slot,
    ':Gem1' => $Gems[0],
    ':Gem2' => $Gems[1],
    ':Gem3' => $Gems[2],
    ':Gem4' => $Gems[3],
    ':Gem5' => $Gems[4],
    ':Gem6' => $Gems[5],
    ':Craftname' => $Craftname
));

$Slot++;
SetSuccessAlert("Item successfully restored to {$Slot} slot of warehouse");
header("Location: $BackUrl");
exit;
