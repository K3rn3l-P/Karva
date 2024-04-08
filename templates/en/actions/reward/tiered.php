<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica ID corretto
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        throw new Exception("Wrong ID");
    }

    $id = $_GET["id"];

    // Trova l'elemento di ricompensa
    $stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Tiered_Spender_Reward_Items WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $rewardItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rewardItem) {
        throw new Exception("Reward not exists");
    }

    $rewardID = $rewardItem["RewardID"];
    $itemID = $rewardItem["ItemID"];
    $count = $rewardItem["Count"];

    // Trova la ricompensa
    $stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Tiered_Spender_Reward WHERE ID = :rewardID");
    $stmt->bindParam(':rewardID', $rewardID, PDO::PARAM_INT);
    $stmt->execute();

    $reward = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reward) {
        throw new Exception("Reward not exists");
    }

    $spenderID = $reward["SpenderID"];
    $required = $reward["AP"];

    // Trova Tiered Spender
    $stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Tiered_Spender WHERE ID = :spenderID AND CURRENT_TIMESTAMP BETWEEN StartDate AND EndDate");
    $stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
    $stmt->execute();

    $tieredSpender = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tieredSpender) {
        throw new Exception("This tiered spender not exists or ended");
    }

    // Verifica se la ricompensa è già stata ricevuta
    $stmt = $conn->prepare("SELECT 1 FROM PS_WebSite.dbo.Tiered_Spender_User_Reward WHERE UserUID = :userUID AND RewardID = :rewardID");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':rewardID', $rewardID, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        throw new Exception("Reward already received");
    }

    // Ottieni il conteggio speso di DP
    $stmt = $conn->prepare("SELECT DP FROM PS_WebSite.dbo.Tiered_Spender_User_Progress WHERE UserUID = :userUID AND SpenderID = :spenderID");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
    $stmt->execute();

    $spended = $stmt->fetchColumn();

    if ($spended < $required) {
        throw new Exception("Not enough");
    }

    // Trova l'elemento
    $stmt = $conn->prepare("SELECT [Count] FROM PS_GameDefs.dbo.Items WHERE ItemID = :itemID");
    $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);
    $stmt->execute();

    $stackCount = (int) $stmt->fetchColumn();

    $reqSlots = ($stackCount === 1) ? (int) $count : 1;

    if ($stackCount === 1) {
        $count = 1;
    }

    // Trova gli slot liberi
    $slots = array();
    $slot = 0;

    while ($slot < 240) {
        $stmt = $conn->prepare("SELECT Slot FROM PS_Billing.dbo.Users_Product WHERE UserUID = :userUID AND Slot = :slot");
        $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
        $stmt->bindParam(':slot', $slot, PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt->fetchColumn()) {
            $slots[] = $slot;
        }

        if (count($slots) == $reqSlots) {
            break;
        }

        $slot++;
    }

    if ($slot == 240) {
        throw new Exception("No free slots");
    }

    // Inserisci gli elementi
    foreach ($slots as $slot) {
        $stmt = $conn->prepare("INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES (:userUID, :slot, :itemID, :count, 'TieredSpender', CURRENT_TIMESTAMP)");
        $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
        $stmt->bindParam(':slot', $slot, PDO::PARAM_INT);
        $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);
        $stmt->bindParam(':count', $count, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Log the adding reward
    $stmt = $conn->prepare("INSERT INTO PS_WebSite.dbo.Tiered_Spender_User_Reward (UserUID, SpenderID, RewardID, RewardItemID) VALUES (:userUID, :spenderID, :rewardID, :itemID)");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
    $stmt->bindParam(':rewardID', $rewardID, PDO::PARAM_INT);
    $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);
    $stmt->execute();

    // Trova le ricompense ricevute e il conteggio totale delle ricompense
$stmt = $conn->prepare("SELECT COUNT(1) AS Cnt FROM PS_WebSite.dbo.Tiered_Spender_User_Reward WHERE SpenderID = :spenderID AND UserUID = :userUID");
$stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
$stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
$stmt->execute();
$recvCount = $stmt->fetchColumn();

// Se l'utente ha ricevuto tutte le ricompense, rimuovi le ricompense e aggiorna i punti spesi
if ($recvCount >= $totalCount) {
    // Rimuovi le ricompense
    $stmt = $conn->prepare("DELETE FROM PS_WebSite.dbo.Tiered_Spender_User_Reward WHERE SpenderID = :spenderID AND UserUID = :userUID");
    $stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->execute();

    // Aggiorna i punti spesi
    $stmt = $conn->prepare("SELECT MAX(AP) AS Max FROM PS_WebSite.dbo.Tiered_Spender_Reward WHERE SpenderID = :spenderID");
    $stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
    $stmt->execute();
    $max = $stmt->fetchColumn();
    $diff = max($spended - $max, 0);

    $stmt = $conn->prepare("UPDATE PS_WebSite.dbo.Tiered_Spender_User_Progress SET DP = :diff WHERE SpenderID = :spenderID AND UserUID = :userUID");
    $stmt->bindParam(':diff', $diff, PDO::PARAM_INT);
    $stmt->bindParam(':spenderID', $spenderID, PDO::PARAM_INT);
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->execute();
}

SetSuccessAlert("Reward added successfully");
header("location: $BackUrl");
} catch (Exception $e) {
    SetErrorAlert($e->getMessage());
    header("location: $BackUrl");
}
