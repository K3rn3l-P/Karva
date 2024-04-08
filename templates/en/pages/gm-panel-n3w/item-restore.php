<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit;
}

// Verifica se l'ID è corretto e numerico
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit;
}

$id = $_GET["id"];
// Trova l'oggetto danneggiato
$stmt = $pdo->prepare("SELECT * FROM PS_GameLog.dbo.BrokenItems WHERE ID = :id");
$stmt->execute(array(':id' => $id));
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Se l'oggetto non esiste
if (!$item) {
    SetErrorAlert("ID not exists");
    header("Location: $BackUrl");
    exit;
}

// Se l'oggetto è già stato ripristinato
if ($item["Res"]) {
    SetErrorAlert("Already restored");
    header("Location: $BackUrl");
    exit;
}

$info = $item["Info"];
$itemID = $item["ItemID"];
$type = floor($itemID / 1000);
$typeID = $itemID % 1000;

// Verifica se l'utente è attualmente in gioco
$stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = :userUID AND LoginStatus = 1");
$stmt->execute(array(':userUID' => $item["UserUID"]));
if ($stmt->fetchColumn()) {
    SetErrorAlert("User currently in game. You must kick him before restoring the item.");
    header("Location: $BackUrl");
    exit;
}

// Estrai gemme e nome artigianale
$gemsStr = trim(substr($info, 0, strpos($info, "(")));
$gems = explode(",", $gemsStr);
$craftname = substr($info, strpos($info, ":") + 1, 20);

// Trova uno slot libero nel magazzino
$slot = 0;
while ($slot <= 240) {
    $stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = :userUID AND Slot = :slot");
    $stmt->execute(array(':userUID' => $item["UserUID"], ':slot' => $slot));
    if (!$stmt->fetchColumn()) break;
    $slot++;
}
// Se non ci sono slot liberi
if ($slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("Location: $BackUrl");
    exit;
}

// Aggiorna lo stato dell'oggetto danneggiato
$stmt = $pdo->prepare("UPDATE PS_GameLog.dbo.BrokenItems SET Res = 1 WHERE ID = :id");
$stmt->execute(array(':id' => $id));

// Inserisci l'oggetto ripristinato nel magazzino
$stmt = $pdo->prepare("INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID, UserUID, ItemID, ItemUID, Type, TypeID, Slot, Quality, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, Craftname, [Count], Maketime, Maketype, Del)
            VALUES (1, :userUID, :itemID, :itemUID, :type, :typeID, :slot, 0, :gem1, :gem2, :gem3, :gem4, :gem5, :gem6, :craftname, 1, CURRENT_TIMESTAMP, 'X', 0)");
$stmt->execute(array(
    ':userUID' => $item["UserUID"],
    ':itemID' => $itemID,
    ':itemUID' => $item["ItemUID"],
    ':type' => $type,
    ':typeID' => $typeID,
    ':slot' => $slot,
    ':gem1' => $gems[0],
    ':gem2' => $gems[1],
    ':gem3' => $gems[2],
    ':gem4' => $gems[3],
    ':gem5' => $gems[4],
    ':gem6' => $gems[5],
    ':craftname' => $craftname
));

$slot++;
SetSuccessAlert("Item successfully restored to slot {$slot} of the warehouse");
header("Location: $BackUrl");
exit;
