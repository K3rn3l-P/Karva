<div class="borderless_col" id="ts_logo" style="padding:0;">
    <img class="tier_logo en" src="<?= $AssetUrl ?>images/tieredspender/en_logo.png" alt="<?= $AssetUrl ?>images/tieredspender/en_logo.png"></div>
<table id="title_table">
    <tbody>
    <tr>
        <td class="borderless_col" id="ts_current_ap">
            <div id="ts_current_spending">Your Current Spending: <b><?= number_format($Spended, 0, '.', ' ') ?></b></div>
            <img src="<?= $AssetUrl ?>images/ap_32x32.png">
        </td>
        <td class="borderless_col" id="ts_time_left">
            <div>This event ends in <b><?= $TS["Days"] ?></b> days.</div>
        </td>
    </tr>
    </tbody>
</table>
<div class="element_container" style="padding:15px; width: 95%;">
    <ul class="evenly">
        <li style="position: absolute; color: white; left: 5px;"><b>SP:</b></li>
        <?php
        $Round = 1;
        foreach ($Rewards as $Reward) {
            if ($Reward["AP"] < $Spended) $Round++;
            echo "<li><span>$Reward[AP]</span></li>";
        }
        ?>
    </ul>
    <ul class="tiered-progress evenly">
        <?php
        $Prev = 0;
        foreach ($Rewards as $Reward) {
            $Width = 0;
            $Class = "";
            if ($Reward["AP"] <= $Spended) {
                $Width = 100;
                $Class = ($Reward["RewardItemID"]) ? "redeemed" : "unlocked";
            }
            else if ($Spended > $Prev) {
                $Width = ($Spended - $Prev) / ($Reward["AP"] - $Prev) * 100;
                $Class = "progress";
            }

            $Prev = $Reward["AP"];
            echo "<li><div class='$Class' style='width: $Width%'></div></li>";
        }
        ?>
    </ul>
    <ul class="evenly">
        <li style="position: absolute; color: white; left: 5px;"><b>Tier:</b></li>
        <?php
        for ($i = 1; $i <= $RewardsCount; $i++)
            echo "<li><span>$i</span></li>";
        ?>
    </ul>
</div>
<div class="round_num element_container margin10L" style="width:100%;">
    <b>Round <?= $Round ?></b>
</div>
<div class="element_container legend_container">
    <b class="legend"><b class="redeemed"></b>Tier Redeemed</b>
    <b class="legend"><b class="unlocked"></b>Tier Unlocked</b>
    <b class="legend"><b class="progress"></b>Rewards Progress</b>
</div>