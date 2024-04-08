<div id="backgroundmsb">
    <div id="iconsb">
        <div id="imageiconeeless">
            <img src="images/shop_icons/<?= $RewardItem["Icon"] ?>" style="width: 40px; height: 40px;">
        </div>

        <div id="headeree">
            <span style="color:#FFD700;"><?= $RewardItem["Title"] ?></span>
        </div>

        <div id="detailee">
            <p><?= $RewardItem["Desc"] ?></p>
        </div>

        <a href="<?= $TemplateUrl ?>actions/reward/pvp-reward.php?id=<?= $RewardItem["ID"] ?>" class="rewards_submit">
            <button id="btn_redeem" class="pointer-link form-submit">REDEEM</button>
        </a>
    </div>
</div>