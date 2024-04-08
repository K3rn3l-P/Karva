<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

// No access
if ($UserInfo["AdminLevel"] < 205) {
    SetErrorAlert("You have no rights for this action");
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

$query = $conn->prepare("DELETE FROM PS_GameData.dbo.CharItems WHERE CharID = ? AND ItemUID = ?");
$result = $query->execute([$CharID, $ItemUID]);
$result ? SetSuccessAlert("Item removed") : SetErrorAlert("Item removing error");

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, '[InventoryManagement] Remove all', 'CHARID: $CharID; ITEMUID: $ItemUID', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
