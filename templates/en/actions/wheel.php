<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$UserUID) {
    echo "You must be logged";
    return;
}

// Controlla se i punti sono sufficienti
if ($Point < WHEEL_COST) {
    echo "Not enough $currencyCode";
    return;
}

// Trova lo slot libero nella banca
$freeSlotQuery = $conn->prepare("SELECT Slot FROM PS_Billing.dbo.Users_Product WHERE UserUID = :userUID ORDER BY Slot");
$freeSlotQuery->execute(['userUID' => $UserUID]);
$slots = $freeSlotQuery->fetchAll(PDO::FETCH_COLUMN);

$slot = 0;
foreach ($slots as $occupiedSlot) {
    if ($occupiedSlot != $slot) 
        break;
    $slot++;
}

// La banca è piena
if ($slot >= 240) {
    echo "No free slots in your Bank Teller!";
    return;
}

// Sottrai i punti necessari
$cost = WHEEL_COST;
$updatePointsQuery = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point = Point - :cost WHERE UserUID = :userUID AND Point >= :cost");
$updatePointsQuery->execute(['cost' => $cost, 'userUID' => $UserUID]);

// Ottieni gli oggetti della ruota
$itemsQuery = $conn->prepare("SELECT * FROM PS_WebSite.dbo.WheelItems WHERE Del = 0 ORDER BY OrderIndex");
$itemsQuery->execute();
$items = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);

$totalRate = array_sum(array_column($items, "Rate"));

// Trova l'oggetto casuale
$rand = rand(0, $totalRate - 1);
$current = 0;
$prizeItem = null;
foreach ($items as $item) {
    $current += $item["Rate"];
    if ($rand < $current) {
        $prizeItem = $item;
        break;
    }
}

if ($prizeItem) {
    // Aggiungi l'oggetto alla banca
    $insertItemQuery = $conn->prepare("INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES (:userUID, :slot, :itemID, :itemCount, 'Fortune Wheel', CURRENT_TIMESTAMP)");
    $insertItemQuery->execute([
        'userUID' => $UserUID,
        'slot' => $slot,
        'itemID' => $prizeItem['ItemID'],
        'itemCount' => $prizeItem['ItemCount']
    ]);

    // Registra l'uso della ruota
    $insertLogQuery = $conn->prepare("INSERT INTO PS_WebSite.dbo.WheelLog (UserUID, PrizeID) VALUES (:userUID, :prizeID)");
    $insertLogQuery->execute(['userUID' => $UserUID, 'prizeID' => $prizeItem['ID']]);

    echo $prizeItem["OrderIndex"];
} else {
    echo "Error: Unable to determine prize item";
}
?>
