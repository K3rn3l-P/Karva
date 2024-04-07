<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Incorrect ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
}

$id = $_GET["id"];
// Find reward item
$odbcResult = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender_Reward_Items WHERE ID={$id}");
// Reward item not exists
if (!odbc_num_rows($odbcResult)) {
    SetErrorAlert("Reward not exists");
    header("location: $BackUrl");
    return;
}
$RewardID = odbc_result($odbcResult, "RewardID");
$ItemID = odbc_result($odbcResult, "ItemID");
$Count = odbc_result($odbcResult, "Count");

// Find reward
$odbcResult = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender_Reward WHERE ID={$RewardID}");
// Reward not exists
if (!odbc_num_rows($odbcResult)) {
    SetErrorAlert("Reward not exists");
    header("location: $BackUrl");
    return;
}
$SpenderID = odbc_result($odbcResult, "SpenderID");
$Required = odbc_result($odbcResult, "AP");

// Find Tiered Spender
$odbcResult = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender WHERE ID={$SpenderID} AND CURRENT_TIMESTAMP BETWEEN StartDate AND EndDate");
// TieredSpender not exists or already ended
if (!odbc_num_rows($odbcResult)) {
    SetErrorAlert("This tiered spender not exists or ended");
    header("location: $BackUrl");
    return;
}

// Check the reward received
$odbcResult = odbc_exec($odbcConn, "SELECT 1 FROM PS_WebSite.dbo.Tiered_Spender_User_Reward WHERE UserUID={$UserUID} AND RewardID={$RewardID}");
// Reward already received
if (odbc_num_rows($odbcResult)) {
    SetErrorAlert("Reward already received");
    header("location: $BackUrl");
    return;
}

// Get spended count of DP
$odbcResult = odbc_exec($odbcConn, "SELECT [DP] FROM PS_WebSite.dbo.Tiered_Spender_User_Progress WHERE UserUID={$UserUID} AND SpenderID={$SpenderID}");
$Spended = odbc_num_rows($odbcResult) ? odbc_result($odbcResult, "DP") : 0;
// Not enough
if ($Spended < $Required) {
    SetErrorAlert("Not enough");
    header("location: $BackUrl");
    return;
}


// Find item
$odbcResult = odbc_exec($odbcConn, "SELECT [Count] FROM PS_GameDefs.dbo.Items WHERE ItemID=$ItemID");
$StackCount = (int)odbc_result($odbcResult, "Count");
// Required count of slots
$ReqSlots = ($StackCount === 1) ? (int)$Count : 1;
// No more than 1 item in stack
if ($StackCount === 1) $Count = 1;

// Find free slots
$Slots = array();
$Slot = 0;
while ($Slot < 240) {
	$odbcResult = odbc_exec($odbcConn, "SELECT Slot FROM PS_Billing.dbo.Users_Product WHERE UserUID={$UserUID} AND Slot=$Slot");
	// Free slot
	if (!odbc_num_rows($odbcResult))
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
	odbc_exec($odbcConn, "INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES ({$UserUID},{$Slot},{$ItemID},{$Count},'TieredSpender',CURRENT_TIMESTAMP)");
}


// Log the adding reward
odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.Tiered_Spender_User_Reward VALUES ({$UserUID}, {$SpenderID}, {$RewardID}, {$ItemID})");


// Find received rewards and total count of rewards
$odbcResult = odbc_exec($odbcConn, "SELECT COUNT(1) AS [Cnt] FROM PS_WebSite.dbo.Tiered_Spender_Reward WHERE SpenderID={$SpenderID}");
$TotalCount = odbc_result($odbcResult, "Cnt");
$odbcResult = odbc_exec($odbcConn, "SELECT COUNT(1) AS [Cnt] FROM PS_WebSite.dbo.Tiered_Spender_User_Reward WHERE SpenderID={$SpenderID} AND UserUID={$UserUID}");
$RecvCount = odbc_result($odbcResult, "Cnt");
// User got all rewards
if ($RecvCount >= $TotalCount) {
	// Remove rewards
    odbc_exec($odbcConn, "DELETE FROM PS_WebSite.dbo.Tiered_Spender_User_Reward WHERE SpenderID={$SpenderID} AND UserUID={$UserUID}");
	// Update spended points
	$odbcResult = odbc_exec($odbcConn, "SELECT MAX(AP) AS [Max] FROM PS_WebSite.dbo.Tiered_Spender_Reward WHERE SpenderID={$SpenderID}");
	$Max = odbc_result($odbcResult, "Max");
	$Diff = $Spended - $Max;
	$Diff = ($Diff > 0) ? $Diff : 0;
    odbc_exec($odbcConn, "UPDATE PS_WebSite.dbo.Tiered_Spender_User_Progress SET DP=$Diff WHERE SpenderID={$SpenderID} AND UserUID={$UserUID}");
}


SetSuccessAlert("Reward added successfully");
header("location: $BackUrl");