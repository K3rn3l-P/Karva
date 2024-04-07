<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}

// Check values
if (!isset($_POST["id"], $_POST["count"]) || !is_numeric($_POST["id"]) || !is_numeric($_POST["count"])) {
	header("Location:$BackUrl");
	return;
}
$id = $_POST["id"];
$count = $_POST["count"];

// Check count
if ($count > 20 || $count < 1) {
	SetErrorAlert("does not allowed more than 20 items for time!");
	header("Location:$BackUrl");
	return;
}
// Create session
if (!isset($_SESSION["products"])) {
	$_SESSION["products"] = array();
}
// Too much products
if (count($_SESSION["products"]) > 20) {
	SetErrorAlert("Too much products!");
	header("Location:$BackUrl");
	return;
}
// Find the product
$result = odbc_exec($odbcConn, "SELECT product_name, price FROM PS_WebSite.dbo.products WHERE id=$id");
if (!odbc_num_rows($result)) {
	SetErrorAlert("Product not exist");
	header("Location:$BackUrl");
	return;
}

// Add product
$_SESSION["products"][$id] = $count;

// Redirect back 
header("Location:$BackUrl");