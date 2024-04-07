<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
	header("location: $BackUrl");
	return;
}

$row =  $_GET['r'];
    
$query1 = $conn->prepare("UPDATE PS_WebSite.dbo.News$lang SET Del=1 WHERE Row = ?");
$query1->bindParam(1, $row, PDO::PARAM_STR);
$query1->execute();

header("location: $BackUrl");
