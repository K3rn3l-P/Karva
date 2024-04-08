<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è uno staff
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Verifica l'ID del personaggio e dell'oggetto
if (!isset($_GET["cid"], $_GET["item"]) || !is_numeric($_GET["cid"]) || !is_numeric($_GET["item"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
}

$CharID = $_GET["cid"];
$ItemUID = $_GET["item"];

// Trova l'oggetto nel personaggio
$stmt = $pdo->prepare("SELECT * FROM PS_GameData.dbo.CharItems WHERE CharID = ? AND ItemUID = ?");
$stmt->execute([$CharID, $ItemUID]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se l'oggetto esiste nel personaggio
if (!$item) {
    SetErrorAlert("Item not exists");
    header("location: $BackUrl");
    return;
}

// Verifica se l'utente è attualmente in gioco
$stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE CharID = ? AND LoginStatus = 1");
$stmt->execute([$CharID]);
if ($stmt->fetchColumn()) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}

// Chiama la stored procedure per cambiare l'oggetto
$stmt = $pdo->prepare("EXEC PS_GameData.dbo.FactionChange_item ?");
$stmt->execute([$ItemUID]);
$procResult = $stmt->fetchColumn();

switch ((int)$procResult) {
    case 1:
        SetSuccessAlert("Item changed");
        break;
    case -1:
        SetErrorAlert("Item doesn't required changing");
        break;
    case -2:
        SetErrorAlert("Can't find analog for this item");
        break;        
    default:
        SetErrorAlert("Unexpected error");
        break;
}

header("location: $BackUrl");
