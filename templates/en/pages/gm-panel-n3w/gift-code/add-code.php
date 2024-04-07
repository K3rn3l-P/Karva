<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Fill all fields
if (!isset($_POST["code"], $_POST["itemid"], $_POST["count"], $_POST["enddate"])) {
    SetErrorAlert("Fill all fields");
    header("location: $BackUrl");
    return;
}

// Get data
$Code = GetClear($_POST['code']);
// Generate random code if not exist
if (!$Code)
	$Code = strtoupper(getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4));

$ItemID = (int)GetClear($_POST['itemid']);
$Count = (int)GetClear($_POST['count']);
$SP = (int)GetClear($_POST['sp']);
$EndDate = GetClear($_POST['enddate']);
$FEU = isset($_POST["feu"]) ? 1 : 0;

// Incorrect numeric
if (!is_numeric($SP) || $SP > 5000) {
    SetErrorAlert("Incorrect SP");
    header("location: $BackUrl");
    return;
}
if (!is_numeric($Count) || $Count > 255) {
    SetErrorAlert("Incorrect Count");
    header("location: $BackUrl");
    return;
}
if (!is_numeric($ItemID) || $ItemID > 255255) {
    SetErrorAlert("Incorrect ItemID");
    header("location: $BackUrl");
    return;
}

// Code exists
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE Code='{$Code}' AND Del=0");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("This code already exists");
    header("location: $BackUrl");
    return;
}

// Add code
odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.GiftCodes (Code, ForEachUser, EndDate, ItemID, Count, SP) VALUES ('{$Code}',$FEU,'{$EndDate}',$ItemID,$Count,$SP)");
			
SetSuccessAlert("Code $Code added");
header("location: $BackUrl");
