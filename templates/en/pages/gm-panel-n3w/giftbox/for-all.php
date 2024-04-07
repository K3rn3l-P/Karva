<?php
$total = 0;
$success = 0;
$fail = 0;

$selectResult = odbc_exec($odbcConn, "SELECT UserUID FROM PS_UserData.dbo.Users_Master");
while (odbc_fetch_row($selectResult)) {
	$userUid = odbc_result($selectResult, "UserUID");
	$total++;
	// Find free slot
	$result = odbc_exec($odbcConn, "SELECT Slot FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID=$userUid ORDER BY [Slot]");
	$slot = 0;
	while (odbc_fetch_row($result)) {
		if ($slot != odbc_result($result, "Slot"))
			break;
		$slot++;
	}
	// No free slots
	if ($slot == 240) {
		$fail++;
		continue;
	}

	// Add item
	$result = odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate)
		VALUES ($userUid, $slot, $itemid, $count, GETDATE())");
	$result ? $success++ : $fail++;
}

// Insert adding log
odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.GiftBox_Log (UserID, ItemID, ItemCount, Slot, ByUser, IP)	
	VALUES ('For all', $itemid, $count, $slot, '$UserID', '$UserIP')");
	
SetSuccessAlert("Item <b>$itemName (x$count)</b> added to all. 
	Total users: <b>$total</b>. 
	Success: <b class='green'>$success</b>. 
	Fail: <b class='red'>$fail</b>");
