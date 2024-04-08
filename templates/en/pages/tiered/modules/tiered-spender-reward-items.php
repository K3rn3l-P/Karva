<?php
$current = $isFirst ? "current" : "";
?>
<li class="<?= $current ?>" id="reward-block-<?= $Reward["ID"] ?>">
    <div class="tier_description">
        <?php
        $color = "#FF0000";
        $title = "To unlock this tier, you need to spend <strong>{$Reward['AP']} SP</strong>!";
        if ($Reward["RewardItemID"]) {
            $color = "#AAAA00";
            $title = "Reward was already received";
        } else if ($Reward["Available"]) {
            $color = "#00FF00";
            $title = "Reward available";
        }
        ?>
        <span style="text-align: center; display:block; padding: 10px; color:<?= $color ?>;"><?= $title ?></span>
        <img src="<?= $AssetUrl ?>images/tieredspender/banner/<?= $Reward["Banner"] ?>" />
        <hr />
        <?php
        $query = "SELECT * FROM PS_WebSite.dbo.Tiered_Spender_Reward_Items WHERE RewardID=:reward_id ORDER BY [ID]";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":reward_id", $Reward["ID"], PDO::PARAM_INT);
        $stmt->execute();
        while ($RewardItem = $stmt->fetch(PDO::FETCH_ASSOC)) {
            include("tiered-spender-reward-items-info.php");
        }
        ?>
    </div>
</li>
