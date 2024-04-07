<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
	header("location: $BackUrl");
	return;
}

$title = $_POST['title'];
$text = $_POST['editor1'];
$image = $_POST['image'];
$category = $_POST['category'];

$query1 = $conn->prepare("INSERT INTO PS_WebSite.dbo.News$lang (Title, Text, Date, Image, Category, Author) VALUES (?,?,GETDATE(),?,?,?)");
$query1->bindParam(1, $title, PDO::PARAM_STR);
$query1->bindParam(2, $text, PDO::PARAM_STR);
$query1->bindParam(3, $image, PDO::PARAM_STR);
$query1->bindParam(4, $category, PDO::PARAM_STR);
$query1->bindParam(5, $UserID, PDO::PARAM_STR);
$query1->execute();

header("location: $BackUrl");
