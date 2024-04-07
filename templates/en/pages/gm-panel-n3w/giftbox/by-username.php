<?php
$sql = "SELECT UserUID, UserID FROM PS_UserData.dbo.Users_Master WHERE UserID='$username'";
$result = odbc_exec($odbcConn, $sql);
// User not exist
if (!odbc_num_rows($result)) {
	SetErrorAlert("User not exist");
	return;
}
$userData = odbc_fetch_array($result);

// Find free slot
$result = odbc_exec($odbcConn, "SELECT Slot FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID=$userData[UserUID] ORDER BY [Slot]");
$slot = 0;
while (odbc_fetch_row($result)) {
	if ($slot != odbc_result($result, "Slot"))
		break;
	$slot++;
}
// Bank is full
if ($slot == 240) {
	SetErrorAlert("GiftBox of user $userData[UserID] is full");
	return;
}

// Insert adding log
odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.GiftBox_Log (UserUID, UserID, ItemID, ItemCount, Slot, ByUser, IP)	
	VALUES ($userData[UserUID], '$userData[UserID]', $itemid, $count, $slot, '$UserID', '$UserIP')");
// Insert item
$result = odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate)
	VALUES ($userData[UserUID], $slot, $itemid, $count, GETDATE())");
$slot++;
if ($result) {
	SetSuccessAlert("Item <b>$itemName (x$count)</b> successfully added to <b>$userData[UserID]</b> ($slot slot)");
} else {
	SetErrorAlert("Adding error");
}