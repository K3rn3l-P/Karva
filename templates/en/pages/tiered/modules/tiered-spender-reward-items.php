<?php
$Current = $IsFirst ? "current" : "";
?>
<li class="<?= $Current ?>" id="reward-block-<?= $Reward["ID"] ?>">
    <div class="tier_description">
        <?php
        $Color = "#FF0000";
        $Title = "To unlock this tier, you need to spend <strong>$Reward[AP] SP</strong>!";
        if ($Reward["RewardItemID"]) {
            $Color = "#AAAA00";
            $Title = "Reward was already received";
        } else if ($Reward["Available"]) {
            $Color = "#00FF00";
            $Title = "Reward available";
        }
        ?>
        <span style="text-align: center; display:block; padding: 10px; color:<?= $Color ?>;"><?= $Title ?></span>
        <image src="<?= $AssetUrl ?>images/tieredspender/banner/<?= $Reward["Banner"] ?>" />
        <hr />
        <?php
        $ItemsRes = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender_Reward_Items WHERE RewardID=$Reward[ID] ORDER BY [ID]");
        while ($RewardItem = odbc_fetch_array($ItemsRes)) {
            include("tiered-spender-reward-items-info.php");
        }
        ?>
    </div>
</li>