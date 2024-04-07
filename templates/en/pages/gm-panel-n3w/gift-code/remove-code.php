<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect id
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Incorrect ID");
    header("location: $BackUrl");
    return;
}
$CodeID = $_GET["id"];

// Code not exists
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE ID=$CodeID AND Del=0");
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Code not exists or already deleted");
    header("location: $BackUrl");
    return;
}

// Remove code
odbc_exec($odbcConn, "UPDATE PS_WebSite.dbo.GiftCodes SET Del=1 WHERE ID=$CodeID");
			
SetSuccessAlert("Code removed");
header("location: $BackUrl");
