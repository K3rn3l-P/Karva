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
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameLog.dbo.BrokenItems WHERE ID={$id}");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("ID not exists");
    header("location: $BackUrl");
    return;
}
$Item = odbc_fetch_array($SqlRes);
// Already restored
if ($Item["Res"]) {
    SetErrorAlert("Already restored");
    header("location: $BackUrl");
    return;
}


$Info = $Item["Info"];
$ItemID = $Item["ItemID"];
$Type = floor($ItemID / 1000);
$TypeID = $ItemID % 1000;

// User currently in game
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID=$Item[UserUID] AND LoginStatus=1");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
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
	$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID=$Item[UserUID] AND Slot=$Slot");
	if (!odbc_num_rows($SqlRes)) break;
	$Slot++;
}
// No free slots
if ($Slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("location: $BackUrl");
    return;
}


$query = $conn->prepare("UPDATE PS_GameLog.dbo.BrokenItems SET Res=1 WHERE ID=$id");
$query->execute();
// Insert the item
$query = $conn->prepare("INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID,UserUID,ItemID,ItemUID,Type,TypeID,Slot,Quality,Gem1,Gem2,Gem3,Gem4,Gem5,Gem6,Craftname,[Count],Maketime,Maketype,Del)
			VALUES (1,$Item[UserUID],$ItemID,$Item[ItemUID],$Type,$TypeID,$Slot,0,$Gems[0],$Gems[1],$Gems[2],$Gems[3],$Gems[4],$Gems[5],'$Craftname',1,CURRENT_TIMESTAMP,'X',0)");
$query->execute();
			
$Slot++;
SetSuccessAlert("Item successfully restored to {$Slot} slot of warehouse");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Restore broken item', 'ID: $id', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");
