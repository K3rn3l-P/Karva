<?php
$Disabled = $Reward["Available"] ? "" : "disabled";
$Link = $Reward["Available"] ? "$TemplateUrl/actions/reward/tiered.php?id=" . $RewardItem["ID"] : "";
?>
<div id="backgroundmsb">
    <div id="iconsb">
        <div id="imageiconeeless">
            <img src="<?= $AssetUrl ?>images/shop_icons/<?= $RewardItem["Image"] ?>" style="width: 40px; height: 40px;">
        </div>

        <div id="headeree">
            <span style="color:#FFD700;"><?= $RewardItem["Name"], " (x", $RewardItem["Count"], ")" ?></span>
        </div>

        <div id="detailee">
            <p><?= $RewardItem["Desc"] ?></p>
        </div>

        <a href="<?= $Link ?>" class="rewards_submit <?= $Disabled ?>">
            <button id="btn_redeem" <?= $Disabled ?> class="form-submit">REDEEM</button>
        </a>
    </div>
</div>