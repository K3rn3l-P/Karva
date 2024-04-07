<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect id
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Incorrect ID");
    header("location: $BackUrl");
    return;
}
$CodeID = $_GET["id"];

$query = $conn->prepare("SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE ID=? AND Del=0");
$query->bindValue(1, $CodeID, PDO::PARAM_INT);
$query->execute();
// Code not exists
if (!$query->rowCount()) {
    SetErrorAlert("Code not exists or already deleted");
    header("location: $BackUrl");
    return;
}

// Remove code
$query = $conn->prepare("UPDATE PS_WebSite.dbo.GiftCodes SET Del=1 WHERE ID=?");
$query->bindValue(1, $CodeID, PDO::PARAM_INT);
$query->execute();
			
SetSuccessAlert("Code removed");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Remove giftcode', 'ID: $CodeID', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");
