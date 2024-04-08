<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_GET["cid"], $_GET["item"]) || !is_numeric($_GET["cid"]) || !is_numeric($_GET["item"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit();
}

$CharID = $_GET["cid"];
$ItemUID = $_GET["item"];

$query = $conn->prepare("SELECT * FROM PS_GameData.dbo.CharItems WHERE CharID = ? AND ItemUID = ?");
$query->execute([$CharID, $ItemUID]);
$Item = $query->fetch(PDO::FETCH_ASSOC);

if (!$Item) {
    SetErrorAlert("Item not exists");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE CharID = ? AND LoginStatus = 1");
$query->execute([$CharID]);
if ($query->fetchColumn()) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("CALL PS_GameData.dbo.FactionChange_item(?, @result)");
$query->execute([$ItemUID]);
$query = $conn->query("SELECT @result as result");
$procResult = $query->fetch(PDO::FETCH_ASSOC);

switch ((int)$procResult['result']) {
    case 1:
        SetSuccessAlert("Item changed");
        break;
    case -1:
        SetErrorAlert("Item doesn't require changing");
        break;
    case -2:
        SetErrorAlert("Can't find analog for this item");
        break;
    default:
        SetErrorAlert("Unexpected error");
        break;
}

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, '[InventoryManagement] Faction changing', 'CHARID: $CharID; ITEMUID: $ItemUID', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
