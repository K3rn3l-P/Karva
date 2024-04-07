<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}

// Check values
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
	header("Location:$BackUrl");
	return;
}
// Check session
if (!isset($_SESSION["products"])) {
	header("Location:$BackUrl");
	return;
}

$id = $_GET["id"];
unset($_SESSION["products"][$id]);

// Redirect back 
header("Location:$BackUrl");