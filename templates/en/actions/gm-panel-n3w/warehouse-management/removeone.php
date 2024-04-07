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

$userUID = $_GET["uid"];
$ItemUID = $_GET["item"];

// Find item
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.UserStoredItems WHERE UserUID={$userUID} AND ItemUID={$ItemUID}");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Item not exists");
    header("location: $BackUrl");
    return;
}
$Item = odbc_fetch_array($SqlRes);
// User currently in game
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID=$userUID AND LoginStatus=1");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}


// Remove item
if ((int)$Item["Count"] <= 1)
	$result = odbc_exec($odbcConn, "DELETE FROM PS_GameData.dbo.UserStoredItems WHERE UserUID={$userUID} AND ItemUID={$ItemUID}");
else
	$result = odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.UserStoredItems SET Count=Count-1 WHERE UserUID={$userUID} AND ItemUID={$ItemUID}");
			
$result ? SetSuccessAlert("One item removed") : SetErrorAlert("Item removing error");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', '[WarehouseManagement] Remove one', 'USERUID: $userUID; ITEMUID: $ItemUID', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");
