<?php
// Ottieni tutti gli UserUID dalla tabella PS_UserData.dbo.Users_Master
$query = "SELECT UserUID FROM PS_UserData.dbo.Users_Master";
$stmt = $pdo->query($query);

// Inizializza le variabili per il conteggio
$total = 0;
$success = 0;
$fail = 0;

// Prepara la query per l'inserimento del log di aggiunta
$insertLogQuery = "INSERT INTO PS_WebSite.dbo.GiftBox_Log (UserID, ItemID, ItemCount, Slot, ByUser, IP)	
                   VALUES (?, ?, ?, ?, ?, ?)";
$stmtInsertLog = $pdo->prepare($insertLogQuery);

// Esegui il loop per ogni UserUID
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $userUid = $row['UserUID'];
    $total++;

    // Trova lo slot libero nella GiftBox dell'utente
    $query = "SELECT TOP 1 Slot FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = ? ORDER BY Slot ASC";
    $stmtSlot = $pdo->prepare($query);
    $stmtSlot->execute([$userUid]);
    $slot = $stmtSlot->fetchColumn();

    // Se la GiftBox dell'utente è piena, incrementa il contatore dei fallimenti e passa all'utente successivo
    if ($slot === false || $slot >= 240) {
        $fail++;
        continue;
    }

    // Inserisci l'oggetto nella GiftBox dell'utente
    $insertItemQuery = "INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate)
                        VALUES (?, ?, ?, ?, GETDATE())";
    $stmtInsertItem = $pdo->prepare($insertItemQuery);
    $result = $stmtInsertItem->execute([$userUid, $slot, $itemid, $count]);

    // Aggiorna i contatori di successo e fallimento in base all'esito dell'inserimento
    $result ? $success++ : $fail++;
}

// Inserisci il log di aggiunta per tutti gli utenti
$stmtInsertLog->execute(['For all', $itemid, $count, $slot, $UserID, $UserIP]);

// Mostra un messaggio di successo con il riepilogo delle operazioni
SetSuccessAlert("Item <b>$itemName (x$count)</b> added to all. 
                 Total users: <b>$total</b>. 
                 Success: <b class='green'>$success</b>. 
                 Fail: <b class='red'>$fail</b>");
?>
