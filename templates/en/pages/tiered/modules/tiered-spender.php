<?php
// Verifica o inserimento del progresso dell'utente nel Tiered Spender
$stmt = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.Tiered_Spender_User_Progress WHERE UserUID=:user_uid AND SpenderID=:spender_id");
$stmt->bindParam(":user_uid", $UserUID, PDO::PARAM_INT);
$stmt->bindParam(":spender_id", $SpenderID, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    $stmt = $pdo->prepare("INSERT INTO PS_WebSite.dbo.Tiered_Spender_User_Progress VALUES (:user_uid, :spender_id, 0)");
    $stmt->bindParam(":user_uid", $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(":spender_id", $SpenderID, PDO::PARAM_INT);
    $stmt->execute();

    // Riesegui la query per ottenere il risultato
    $stmt = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.Tiered_Spender_User_Progress WHERE UserUID=:user_uid AND SpenderID=:spender_id");
    $stmt->bindParam(":user_uid", $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(":spender_id", $SpenderID, PDO::PARAM_INT);
    $stmt->execute();
}

$progress = $stmt->fetch(PDO::FETCH_ASSOC);
$Spended = $progress ? $progress["DP"] : 0;

// Recupero delle ricompense disponibili per il Tiered Spender
$query = "SELECT R.*, RewardItemID FROM PS_WebSite.dbo.Tiered_Spender_Reward R
        LEFT JOIN PS_WebSite.dbo.Tiered_Spender_User_Reward UR ON UR.UserUID=:user_uid AND UR.RewardID=R.ID
        WHERE R.SpenderID=:spender_id ORDER BY [AP]";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":user_uid", $UserUID, PDO::PARAM_INT);
$stmt->bindParam(":spender_id", $SpenderID, PDO::PARAM_INT);
$stmt->execute();

$Rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);
$RewardsCount = count($Rewards);

// Aggiungere il flag "Available" per indicare se la ricompensa è disponibile
foreach ($Rewards as &$Reward) {
    $Reward["Available"] = ($Reward["AP"] <= $Spended) && (!$Reward["RewardItemID"]);
}
unset($Reward); // Unset della referenza

// Includi i moduli per il progresso e le ricompense del Tiered Spender
?>
<div id="form_wrapper">
    <div id="form_tiered_spender_display">
        <div id="tiered_spender_wrapper">
            <div id="tracker_container">
                <?php include("tiered-spender-progress.php") ?>
                <?php include("tiered-spender-rewards.php") ?>
            </div>
        </div>
    </div>
</div>
