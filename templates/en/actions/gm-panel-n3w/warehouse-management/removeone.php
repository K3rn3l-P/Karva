<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_GET["uid"], $_GET["item"]) || !is_numeric($_GET["uid"]) || !is_numeric($_GET["item"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit();
}

$userUID = $_GET["uid"];
$ItemUID = $_GET["item"];

$query = $conn->prepare("SELECT * FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = ? AND ItemUID = ?");
$query->execute([$userUID, $ItemUID]);
$row = $query->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    SetErrorAlert("Item does not exist");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = ? AND LoginStatus = 1");
$query->execute([$userUID]);
if ($query->fetchColumn()) {
    SetErrorAlert("User is currently in game. You must kick them out before removing the item.");
    header("Location: $BackUrl");
    exit();
}

// Remove item
$query = $conn->prepare("UPDATE PS_GameData.dbo.UserStoredItems SET Count = CASE WHEN Count > 1 THEN Count - 1 ELSE 0 END WHERE UserUID = ? AND ItemUID = ?");
$result = $query->execute([$userUID, $ItemUID]);

if ($result) {
    SetSuccessAlert("One item removed");
} else {
    SetErrorAlert("Error occurred while removing the item");
}

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, '[WarehouseManagement] Remove one', 'USERUID: $userUID; ITEMUID: $ItemUID', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
