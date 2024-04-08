<?php
$total = 0;
$success = 0;
$fail = 0;

$selectQuery = "SELECT UserUID FROM PS_UserData.dbo.Users_Master";
$selectStmt = $odbcConn->prepare($selectQuery);
$selectStmt->execute();

while ($userRow = $selectStmt->fetch(PDO::FETCH_ASSOC)) {
    $userUid = $userRow['UserUID'];
    $total++;
    
    // Find free slot
    $freeSlotQuery = "SELECT Slot FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = ? ORDER BY Slot";
    $freeSlotStmt = $odbcConn->prepare($freeSlotQuery);
    $freeSlotStmt->execute([$userUid]);
    $slot = 0;
    while ($slotRow = $freeSlotStmt->fetch(PDO::FETCH_ASSOC)) {
        if ($slot != $slotRow['Slot'])
            break;
        $slot++;
    }
    // No free slots
    if ($slot == 240) {
        $fail++;
        continue;
    }

    // Add item
    $insertQuery = "INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES (?, ?, ?, ?, GETDATE())";
    $insertStmt = $odbcConn->prepare($insertQuery);
    $result = $insertStmt->execute([$userUid, $slot, $itemid, $count]);
    $result ? $success++ : $fail++;
}

// Insert adding log
$insertLogQuery = "INSERT INTO PS_WebSite.dbo.GiftBox_Log (UserID, ItemID, ItemCount, Slot, ByUser, IP) VALUES ('For all', ?, ?, ?, ?, ?)";
$insertLogStmt = $conn->prepare($insertLogQuery);
$insertLogStmt->execute([$itemid, $count, $slot, $UserID, $UserIP]);

SetSuccessAlert("Item <b>$itemName (x$count)</b> added to all. 
    Total users: <b>$total</b>. 
    Success: <b class='green'>$success</b>. 
    Fail: <b class='red'>$fail</b>");

// Log action
$logActionQuery = "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP]) VALUES (?, ?, 'Adding item to all users', 'ITEM: $itemid; COUNT: $count', ?)";
$logActionStmt = $conn->prepare($logActionQuery);
$logActionStmt->execute([$UserUID, $UserID, $UserIP]);
?>
