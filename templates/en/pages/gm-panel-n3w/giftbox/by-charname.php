<?php
// Seleziona l'utente corrispondente al nome del personaggio fornito
$query = "SELECT TOP 1 UserUID, UserID FROM PS_GameData.dbo.Chars WHERE CharName = ? AND Del = 0";
$stmt = $pdo->prepare($query);
$stmt->execute([$charname]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Se l'utente non esiste, mostra un messaggio di errore
if (!$userData) {
    SetErrorAlert("User does not exist");
    return;
}

// Trova lo slot libero nella GiftBox dell'utente
$query = "SELECT TOP 1 Slot FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = ? ORDER BY Slot ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([$userData['UserUID']]);
$slot = $stmt->fetchColumn();

// Se la GiftBox dell'utente è piena, mostra un messaggio di errore
if ($slot === false || $slot >= 240) {
    SetErrorAlert("GiftBox of user {$userData['UserID']} is full");
    return;
}

// Inserisci il log di aggiunta
$query = "INSERT INTO PS_WebSite.dbo.GiftBox_Log (UserUID, UserID, ItemID, ItemCount, Slot, ByUser, IP)	
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($query);
$params = [
    $userData['UserUID'],
    $userData['UserID'],
    $itemid,
    $count,
    $slot,
    $UserID,
    $UserIP
];
$stmt->execute($params);

// Inserisci l'oggetto nella GiftBox dell'utente
$query = "INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate)
          VALUES (?, ?, ?, ?, GETDATE())";
$stmt = $pdo->prepare($query);
$params = [
    $userData['UserUID'],
    $slot,
    $itemid,
    $count
];
$result = $stmt->execute($params);

// Se l'inserimento è riuscito, mostra un messaggio di successo, altrimenti mostra un messaggio di errore
if ($result) {
    SetSuccessAlert("Item <b>$itemName (x$count)</b> successfully added to <b>{$userData['UserID']}</b> ($slot slot)");
} else {
    SetErrorAlert("Adding error");
}
?>
