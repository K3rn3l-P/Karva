<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit();
}

$id = $_GET["id"];

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.CharQuests WHERE RowID = ?");
$query->execute([$id]);
if ($query->rowCount() == 0) {
    SetErrorAlert("Row does not exist");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("DELETE FROM PS_GameData.dbo.CharQuests WHERE RowID = ?");
$query->execute([$id]);

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, ?, ?, ?)");
$query->execute([$UserUID, $UserID, 'Quest remove', "ID: $id", $UserIP]);

SetSuccessAlert("Quest successfully removed");
header("Location: $BackUrl");
exit();
?>
