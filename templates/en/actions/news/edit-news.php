<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
	header("location: $BackUrl");
	return;
}

$title = $_POST['title'];
$text = $_POST['editor1'];
$category = $_POST['category'];
$image = $_POST['image'];
$row =  $_POST['new'];
    
$query1 = $conn->prepare("UPDATE PS_WebSite.dbo.News$lang SET Title = ?, Text = ?, Category = ?, Image = ? WHERE Row = ?");
$query1->bindParam(1, $title, PDO::PARAM_STR);
$query1->bindParam(2, $text, PDO::PARAM_STR);
$query1->bindParam(3, $category, PDO::PARAM_STR);
$query1->bindParam(4, $image, PDO::PARAM_STR);
$query1->bindParam(5, $row, PDO::PARAM_STR);
$query1->execute();

header("location: $BackUrl");
