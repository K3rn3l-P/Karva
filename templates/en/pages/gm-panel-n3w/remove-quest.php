<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
}

$id = $_GET["id"];
// Find broken item
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.CharQuests WHERE RowID={$id}");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Row not exists");
    header("location: $BackUrl");
    return;
}

// Insert the item
odbc_exec($odbcConn, "DELETE FROM PS_GameData.dbo.CharQuests WHERE RowID=$id");
			
SetSuccessAlert("Quest successfully removed");
header("location: $BackUrl");
