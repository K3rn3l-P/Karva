<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$IsGM) {
	header("location: $BackUrl");
	return;
}
    
$itemName = $_GET['name'];

$queryItemID = $conn->prepare("SELECT ItemID FROM PS_GameDefs.dbo.items WHERE ItemName = ?");
$queryItemID->bindValue(1, $itemName, PDO::PARAM_INT);
$queryItemID->execute();
$ItemID = $queryItemID->fetch(PDO::FETCH_NUM);

echo 'ItemID = '.$ItemID[0];
