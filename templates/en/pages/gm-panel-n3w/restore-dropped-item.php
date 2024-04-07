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
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameLog.dbo.ActionLog WHERE row={$id}");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("ID not exists");
    header("location: $BackUrl");
    return;
}
$Item = odbc_fetch_array($SqlRes);


$Info = $Item["Text2"];
$ItemUID = $Item["Value1"];
$ItemID = $Item["Value2"];
$Type = floor($ItemID / 1000);
$TypeID = $ItemID % 1000;

// User currently in game
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID=$Item[UserUID] AND LoginStatus=1");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}

// Check the item already exists
$SqlRes1 = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID=$ItemUID");
$SqlRes2 = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID=$ItemUID");
$SqlRes3 = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID=$ItemUID");
if (odbc_num_rows($SqlRes1) || odbc_num_rows($SqlRes2) || odbc_num_rows($SqlRes3)) {
    SetErrorAlert("Item already restored");
    header("location: $BackUrl");
    return;
}


// Get gems and craftname
$GemsStr = trim(substr($Info, 0, strpos($Info, "(")));
$Gems = split(",", $GemsStr);
$Craftname = substr($Info, strpos($Info, ":") + 1, 20);
// Find free slot
$Slot = 0;
while ($Slot <= 240) {
	$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID=$Info[UserUID] AND Slot=$Slot");
	if (!odbc_num_rows($SqlRes))
		break;
	$Slot++;
}
// No free slots
if ($Slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("location: $BackUrl");
    return;
}

// Insert the item
odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID,UserUID,ItemID,ItemUID,Type,TypeID,Slot,Quality,Gem1,Gem2,Gem3,Gem4,Gem5,Gem6,Craftname,[Count],Maketime,Maketype,Del)
			VALUES (1,$Item[UserUID],$ItemID,$ItemUID,$Type,$TypeID,$Slot,0,$Gems[0],$Gems[1],$Gems[2],$Gems[3],$Gems[4],$Gems[5],'$Craftname',1,CURRENT_TIMESTAMP,'X',0)");
			
$Slot++;
SetSuccessAlert("Item successfully restored to {$Slot} slot of warehouse");
header("location: $BackUrl");
