<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
	header("location: $BackUrl");
	return;
}

$title = $_POST['title'];
$text = $_POST['editor1'];
$date= $_POST['date'].' '.sprintf("%02d", $_POST['hour']).':'.sprintf("%02d", $_POST['minute']).':00.000';
$minEnd = $_POST['minuteEnd'];

$query1 = $conn->prepare("INSERT INTO PS_WebSite.dbo.Events$lang VALUES (?,?,?,?)");
$query1->bindParam(1, $title, PDO::PARAM_STR);
$query1->bindParam(2, $text, PDO::PARAM_STR);
$query1->bindParam(3, $date, PDO::PARAM_STR);
$query1->bindParam(4, $minEnd, PDO::PARAM_STR);
$query1->execute();

header("location: $BackUrl");