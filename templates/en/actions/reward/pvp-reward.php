<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
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
// Find reward item
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.PvPReward_Items WHERE ID={$id}");
// Reward item not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Reward not exists");
    header("location: $BackUrl");
    return;
}
$RewardID = odbc_result($SqlRes, "RewardID");
$ItemID = odbc_result($SqlRes, "ItemID");
$Count = odbc_result($SqlRes, "ItemCount");

// Find reward
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.PvPReward WHERE ID={$RewardID}");
// Reward not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Reward not exists");
    header("location: $BackUrl");
    return;
}
$Required = odbc_result($SqlRes, "Kills");
$SP = odbc_result($SqlRes, "SP");

// Check time is over
$SqlRes = odbc_exec($odbcConn, "SELECT ISNULL(DATEDIFF(HOUR, MAX(DT), CURRENT_TIMESTAMP), 999) AS [DateDiff] FROM PS_WebSite.dbo.PvPReward_User_Log WHERE UserUID={$UserUID}");
$CanRedeem = odbc_result($SqlRes, "DateDiff") >= 12;
if (!$CanRedeem) {
    SetErrorAlert("You can't redeem now");
    header("location: $BackUrl");
    return;
}


// Check the reward received
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_WebSite.dbo.PvPReward_User_Log WHERE UserUID={$UserUID} AND RewardID={$RewardID}");
// Reward already received
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("Reward already received");
    header("location: $BackUrl");
    return;
}

// Get kills
$SqlRes = odbc_exec($odbcConn, "SELECT ISNULL(MAX(K1),0) AS [K1] FROM PS_GameData.dbo.Chars WHERE UserUID=$UserUID");
$Kills = odbc_result($SqlRes, "K1");
// Not enough
if ($Kills < $Required) {
    SetErrorAlert("Not enough kills");
    header("location: $BackUrl");
    return;
}

// Check the order
$SqlRes = odbc_exec($odbcConn, "select 1 from PS_WebSite.dbo.PvPReward r 
									left join PS_WebSite.dbo.PvPReward_User_Log l on r.ID=l.RewardID and l.UserUID=$UserUID
									where r.Kills<$Required and UserUID is null");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("You must redeem previous reward");
    header("location: $BackUrl");
    return;
}


// Find item
$SqlRes = odbc_exec($odbcConn, "SELECT [Count] FROM PS_GameDefs.dbo.Items WHERE ItemID=$ItemID");
$StackCount = (int)odbc_result($SqlRes, "Count");
// Required count of slots
$ReqSlots = ($StackCount === 1) ? (int)$Count : 1;
// No more than 1 item in stack
if ($StackCount === 1) $Count = 1;

// Find free slots
$Slots = array();
$Slot = 0;
while ($Slot < 240) {
	$SqlRes = odbc_exec($odbcConn, "SELECT Slot FROM PS_Billing.dbo.Users_Product WHERE UserUID={$UserUID} AND Slot=$Slot");
	// Free slot
	if (!odbc_num_rows($SqlRes))
		$Slots[] = $Slot;
	// Enough
	if (count($Slots) == $ReqSlots) 
		break;
	$Slot++;
}
// Bank is full
if ($Slot == 240) {
    SetErrorAlert("No free slots");
	header("location: $BackUrl");
	return;
}
// Insert items
foreach ($Slots as $Slot) {
	odbc_exec($odbcConn, "INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES ($UserUID,$Slot,$ItemID,$Count,'PvPReward',CURRENT_TIMESTAMP)");
}
// Add points
odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Point+=$SP WHERE UserUID=$UserUID");
// Log the adding reward
odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.PvPReward_User_Log (UserUID, RewardID, RewardItemID) VALUES ($UserUID, $RewardID, $ItemID)");

SetSuccessAlert("Reward added successfully");
header("location: $BackUrl");