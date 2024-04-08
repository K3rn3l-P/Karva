<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è loggato
if (!$UserUID) {
    header("Location:$BackUrl");
    exit();
}

// Verifica se è stato fornito un ID corretto
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    $_SESSION['error_message'] = "Wrong ID";
    header("Location: $BackUrl");
    exit();
}

$id = $_GET["id"];

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Trova l'elemento di ricompensa
    $stmt = $conn->prepare("SELECT * FROM PvPReward_Items WHERE ID=:id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rewardItem = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se l'elemento di ricompensa non esiste
    if (!$rewardItem) {
        $_SESSION['error_message'] = "Reward not exists";
        header("Location: $BackUrl");
        exit();
    }

    $RewardID = $rewardItem["RewardID"];
    $ItemID = $rewardItem["ItemID"];
    $Count = $rewardItem["ItemCount"];

    // Trova la ricompensa
    $stmt = $conn->prepare("SELECT * FROM PvPReward WHERE ID=:rewardID");
    $stmt->bindParam(':rewardID', $RewardID, PDO::PARAM_INT);
    $stmt->execute();
    $reward = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se la ricompensa non esiste
    if (!$reward) {
        $_SESSION['error_message'] = "Reward not exists";
        header("Location: $BackUrl");
        exit();
    }

    $Required = $reward["Kills"];
    $SP = $reward["SP"];

    // Verifica se è passato il tempo necessario per riscattare la ricompensa
    $stmt = $conn->prepare("SELECT IFNULL(TIMESTAMPDIFF(HOUR, MAX(DT), NOW()), 999) AS DateDiff FROM PvPReward_User_Log WHERE UserUID=:userUID");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $CanRedeem = $row["DateDiff"] >= 12;
    if (!$CanRedeem) {
        $_SESSION['error_message'] = "You can't redeem now";
        header("Location: $BackUrl");
        exit();
    }

    // Verifica se la ricompensa è già stata ricevuta
    $stmt = $conn->prepare("SELECT 1 FROM PvPReward_User_Log WHERE UserUID=:userUID AND RewardID=:rewardID");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':rewardID', $RewardID, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $_SESSION['error_message'] = "Reward already received";
        header("Location: $BackUrl");
        exit();
    }

    // Ottieni il numero di uccisioni dell'utente
    $stmt = $conn->prepare("SELECT IFNULL(MAX(K1), 0) AS K1 FROM Chars WHERE UserUID=:userUID");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $Kills = $row["K1"];

    // Verifica se ci sono abbastanza uccisioni per la ricompensa
    if ($Kills < $Required) {
        $_SESSION['error_message'] = "Not enough kills";
        header("Location: $BackUrl");
        exit();
    }

    // Verifica se è stato già ricevuto l'ordine di ricompensa precedente
    $stmt = $conn->prepare("SELECT 1 FROM PvPReward r LEFT JOIN PvPReward_User_Log l ON r.ID=l.RewardID AND l.UserUID=:userUID WHERE r.Kills<:required AND l.UserUID IS NULL");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':required', $Required, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $_SESSION['error_message'] = "You must redeem previous reward";
        header("Location: $BackUrl");
        exit();
    }

    // Ottieni il numero di elementi nello stack
    $stmt = $conn->prepare("SELECT Count FROM Items WHERE ItemID=:itemID");
    $stmt->bindParam(':itemID', $ItemID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $StackCount = (int)$row["Count"];

    // Calcola il numero di slot richiesti
    $ReqSlots = ($StackCount === 1) ? (int)$Count : 1;
    // Se c'è solo un elemento nello stack, imposta Count a 1
    if ($StackCount === 1) {
        $Count = 1;
    }

    // Trova gli slot liberi
    $Slots = array();
    $Slot = 0;
    while ($Slot < 240) {
        $stmt = $conn->prepare("SELECT Slot FROM Users_Product WHERE UserUID=:userUID AND Slot=:slot");
        $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
        $stmt->bindParam(':slot', $Slot, PDO::PARAM_INT);
        $stmt->execute();
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            $Slots[] = $Slot;
        }
        if (count($Slots) == $ReqSlots) {
            break;
        }
        $Slot++;
    }

    // Se il banco dell'utente è pieno
    if ($Slot == 240) {
        $_SESSION['error_message'] = "No free slots";
        header("Location: $BackUrl");
        exit();
    }

    // Inserisci gli elementi nei slot
    foreach ($Slots as $Slot) {
        $stmt = $conn->prepare("INSERT INTO Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES (:userUID, :slot, :itemID, :count, 'PvPReward', NOW())");
        $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
        $stmt->bindParam(':slot', $Slot, PDO::PARAM_INT);
        $stmt->bindParam(':itemID', $ItemID, PDO::PARAM_INT);
        $stmt->bindParam(':count', $Count, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Aggiungi i punti SP
    $stmt = $conn->prepare("UPDATE Users_Master SET Point=Point+:sp WHERE UserUID=:userUID");
    $stmt->bindParam(':sp', $SP, PDO::PARAM_INT);
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->execute();

    // Registra la ricompensa aggiunta
    $stmt = $conn->prepare("INSERT INTO PvPReward_User_Log (UserUID, RewardID, RewardItemID) VALUES (:userUID, :rewardID, :itemID)");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':rewardID', $RewardID, PDO::PARAM_INT);
    $stmt->bindParam(':itemID', $ItemID, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success_message'] = "Reward added successfully";
    header("Location: $BackUrl");
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    header("Location: $BackUrl");
    exit();
} finally {
    $conn = null;
}
