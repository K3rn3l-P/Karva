<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

$username = isset($_POST["username"]) ? GetClear($_POST['username']) : "";
$charname = isset($_POST["charname"]) ? GetClear($_POST['charname']) : "";
$forAll = isset($_POST["feu"]) ? 1 : 0;
$count = $_POST['count'];
$itemid = $_POST['itemid'];

if ((!$username && !$charname && !$forAll) || !is_numeric($count) || !is_numeric($itemid)) {
	SetErrorAlert("Fill all fields");
	header("location:$BackUrl");
	return;
}
if (!$count || $count > 255) {
	SetErrorAlert("Wrong item count");
	header("location:$BackUrl");
	return;
}
if ($itemid < 1001 || $itemid > 255255) {
	SetErrorAlert("Wrong ItemID");
	header("location:$BackUrl");
	return;
}
$result = odbc_exec($odbcConn, "SELECT ItemName FROM PS_GameDefs.dbo.Items WHERE ItemID=$itemid");
if (!odbc_num_rows($result)) {
	$label = number_format($itemid, 0, '.', ' ');
	SetErrorAlert("Item $label not exist");
	header("location:$BackUrl");
	return;
}
$itemName = odbc_result($result, "ItemName");

if ($username) {
	include_once("by-username.php");
} elseif ($charname) {
	include_once("by-charname.php");
} elseif ($forAll) {
	include_once("for-all.php");
}

header("location:$BackUrl");
