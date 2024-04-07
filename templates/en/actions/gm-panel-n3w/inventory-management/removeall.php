<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}
// No access
if ($UserInfo["AdminLevel"] < 205) {
    SetErrorAlert("You have no rights for this action");
    header("Location:$BackUrl");
    return;
}

// Incorrect ID
if (!isset($_GET["cid"], $_GET["item"]) || !is_numeric($_GET["cid"]) || !is_numeric($_GET["item"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
}

$CharID = $_GET["cid"];
$ItemUID = $_GET["item"];

// Find item
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.CharItems WHERE CharID={$CharID} AND ItemUID={$ItemUID}");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Item not exists");
    header("location: $BackUrl");
    return;
}
// User currently in game
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Chars WHERE CharID=$CharID AND LoginStatus=1");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}

// Remove item
$result = odbc_exec($odbcConn, "DELETE FROM PS_GameData.dbo.CharItems WHERE CharID={$CharID} AND ItemUID={$ItemUID}");
$result ? SetSuccessAlert("Item removed") : SetErrorAlert("Item removing error");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', '[InventoryManagement] Remove all', 'CHARID: $CharID; ITEMUID: $ItemUID', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");

