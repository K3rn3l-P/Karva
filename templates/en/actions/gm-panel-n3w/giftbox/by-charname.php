<?php
$query = $conn->prepare("SELECT UserUID, UserID FROM PS_GameData.dbo.Chars WHERE CharName = ? AND Del = 0");
$query->execute([$charname]);
$userData = $query->fetch(PDO::FETCH_ASSOC);

// User not exist
if (!$userData) {
    SetErrorAlert("User not exist");
    return;
}

// Find free slot
$freeSlotQuery = "SELECT Slot FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = ? ORDER BY Slot";
$freeSlotStmt = $odbcConn->prepare($freeSlotQuery);
$freeSlotStmt->execute([$userData['UserUID']]);
$slot = 0;
while ($slotRow = odbc_fetch_row($freeSlotStmt)) {
    if ($slot != $slotRow['Slot'])
        break;
    $slot++;
}
// Bank is full
if ($slot == 240) {
    SetErrorAlert("GiftBox of user {$userData['UserID']} is full");
    return;
}

// Insert adding log
$insertLogQuery = "INSERT INTO PS_WebSite.dbo.GiftBox_Log (UserUID, UserID, ItemID, ItemCount, Slot, ByUser, IP) VALUES (?, ?, ?, ?, ?, ?, ?)";
$insertLogStmt = $conn->prepare($insertLogQuery);
$insertLogStmt->execute([$userData['UserUID'], $userData['UserID'], $itemid, $count, $slot, $UserID, $UserIP]);

// Insert item
$insertItemQuery = "INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES (?, ?, ?, ?, GETDATE())";
$insertItemStmt = $odbcConn->prepare($insertItemQuery);
$result = $insertItemStmt->execute([$userData['UserUID'], $slot, $itemid, $count]);
$slot++;

if ($result) {
    SetSuccessAlert("Item <b>$itemName (x$count)</b> successfully added to <b>{$userData['UserID']}</b> ($slot slot)");
} else {
    SetErrorAlert("Adding error");
}

// Log action
$logActionQuery = "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP]) VALUES (?, ?, 'Adding item to giftbox by charname', 'ITEM: $itemid; COUNT: $count; UID: {$userData['UserUID']}; USERNAME: {$userData['UserID']}', ?)";
$logActionStmt = $conn->prepare($logActionQuery);
$logActionStmt->execute([$UserUID, $UserID, $UserIP]);
?>
