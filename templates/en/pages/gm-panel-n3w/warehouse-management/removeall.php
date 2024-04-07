<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect ID
if (!isset($_GET["uid"], $_GET["item"]) || !is_numeric($_GET["uid"]) || !is_numeric($_GET["item"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
}

$UserUID = $_GET["uid"];
$ItemUID = $_GET["item"];

// Find item
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.UserStoredItems WHERE UserUID={$UserUID} AND ItemUID={$ItemUID}");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Item not exists");
    header("location: $BackUrl");
    return;
}
$Item = odbc_fetch_array($SqlRes);
// User currently in game
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID=$UserUID AND LoginStatus=1");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}

// Remove item
$result = odbc_exec($odbcConn, "DELETE FROM PS_GameData.dbo.UserStoredItems WHERE UserUID={$UserUID} AND ItemUID={$ItemUID}");			
$result ? SetSuccessAlert("Item removed") : SetErrorAlert("Item removing error");
header("location: $BackUrl");
