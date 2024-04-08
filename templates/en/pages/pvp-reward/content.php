<?php
// Inizializzazione delle variabili
$Kills = 0;
$CanRedeem = true;

// Verifica se l'utente è loggato
if ($UserUID) {
    // Query per ottenere il numero massimo di uccisioni e il tempo trascorso dall'ultima ricompensa
    $stmt = $pdo->prepare("SELECT ISNULL(MAX(K1),0) AS K1, ISNULL(DATEDIFF(HOUR, MAX(L.DT), CURRENT_TIMESTAMP), 999) AS DateDiff
                            FROM PS_GameData.dbo.Chars C
                            LEFT JOIN PS_WebSite.dbo.PvPReward_User_Log AS L ON L.UserUID = :user_uid
                            WHERE C.UserUID = :user_uid");
    $stmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $Kills = $result["K1"];
    $DateDiff = $result["DateDiff"];
    $CanRedeem = $DateDiff >= 12;
    $remain = 12 - $DateDiff;
}
?>

<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>Rewards</a> &rarr; <i >PvP Reward</i></span>
    </div>
    
    <div class="page-body border_box self_clear">
        <div style="text-align:right;">
            <a class="nice_button nice_active support-button" href="./?p=pvp-reward">PvP Rewards</a>
            <a class="nice_button support-button" href="/?p=grb-reward">GRB Rewards</a>
        </div>
        <br>
        <h1 class="red center">
            <?= $CanRedeem ? "" : "You can redeem next after $remain hours" ?>
        </h1>
        
        <table class="table-bordered  center" style="width: 100%">
            <tr>
                <th>Kills</th>
                <th>Icon</th>
                <th>SP Reward</th>
                <th>Redeem</th>
            </tr>
            <?php
            // Query per ottenere le ricompense PvP
            $stmt = $pdo->prepare("SELECT R.*, L.UserUID FROM PS_WebSite.dbo.PvPReward AS R
                                    LEFT JOIN PS_WebSite.dbo.PvPReward_User_Log AS L ON L.RewardID = R.ID AND L.UserUID = :user_uid
                                    ORDER BY Kills");
            $stmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
            $stmt->execute();

            while ($Reward = $stmt->fetch(PDO::FETCH_ASSOC)) {
                include("modules/reward-info.php");
            }
            ?>
        </table>
        
    </div>
</div>
