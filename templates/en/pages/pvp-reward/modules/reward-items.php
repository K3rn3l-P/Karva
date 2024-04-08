<div class="popup-redeem" id="redeem-<?= htmlspecialchars($Reward["ID"]) ?>">
    <a onclick="HideItems(<?= htmlspecialchars($Reward["ID"]) ?>)" class="right white pointer-link">Close</a>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.PvPReward_Items WHERE RewardID = :reward_id ORDER BY ID");
    $stmt->bindParam(':reward_id', $Reward["ID"], PDO::PARAM_INT);
    $stmt->execute();
    
    while ($RewardItem = $stmt->fetch(PDO::FETCH_ASSOC)) {
        include("reward-items-info.php");
    }
    ?>
</div>
