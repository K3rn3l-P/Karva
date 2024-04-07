<?php
$query = $conn->prepare("SELECT UserUID, UserID FROM PS_UserData.dbo.Users_Master WHERE UserID='$username'");
$query->execute();
$userData = $query->fetch(PDO::FETCH_ASSOC);
// User not exist
if (!$userData) {
	SetErrorAlert("User not exist");
	return;
}

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

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Adding item to giftbox by username', 'ITEM: $itemid; COUNT: $count; UID: $userData[UserUID]; USERNAME: $userData[UserID]', '$UserIP')");
$query->execute();