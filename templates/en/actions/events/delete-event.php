<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
	header("location: $BackUrl");
	return;
}

$row =  $_GET['r'];
    
$query1 = $conn->prepare("DELETE FROM PS_WebSite.dbo.Events$lang WHERE Row = ?");
$query1->bindParam(1, $row, PDO::PARAM_STR);
$query1->execute();

header("location: $BackUrl");
