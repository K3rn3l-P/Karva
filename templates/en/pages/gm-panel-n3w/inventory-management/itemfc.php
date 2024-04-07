<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
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
$Item = odbc_fetch_array($SqlRes);
// User currently in game
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Chars WHERE CharID=$CharID AND LoginStatus=1");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}

$query = "DECLARE @result int
exec @result = PS_GameData.dbo.FactionChange_item $ItemUID
select @result as result";
$result = odbc_exec($odbcConn, $query);
$procResult = odbc_result($result, "result");
switch ((int)$procResult) {
	case 1:
		SetSuccessAlert("Item changed");
		break;
	case -1:
		SetErrorAlert("Item doesn't required changing");
		break;
	case -2:
		SetErrorAlert("Can't find analog for this item");
		break;		
	default:
		SetErrorAlert("Unexpected error");
		break;
}

header("location: $BackUrl");
