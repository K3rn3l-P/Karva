<div id="rewards">
    <div class="rewards_title">Rewards</div>
    <div class="tab_container">
        <ul id="tabs1" class="tier_tabs">
            <?php
            $IsFirst = true;
            foreach ($Rewards as $Reward) {
                $Current = $IsFirst ? "current" : "";
                $Locked = ($Spended < $Reward["AP"]) ? "<span class='tier_lock'>Locked</span>" : "";
                $LockedImg = ($Spended < $Reward["AP"]) ? "<span class='img_lock'></span>" : "";
                echo "
                        <li id='reward-link-$Reward[ID]' class='$Current'>
                            <a class='tier_wrapper' onclick='showRewards($Reward[ID]);'>
                                <span class='tier_tab'>
                                    $Locked
                                    <b class='tier_num'>$Reward[Name]</b>
                                    <b class='tier_name'></b>
                                </span>
                                <span class='tier_img'>
                                    <img src='$AssetUrl/images/shop_icons/$Reward[Image]'>
                                    $LockedImg
                                </span>
                            </a>
                        </li>";
                $IsFirst = false;
            }
            ?>
        </ul>
    </div>
    <div class="content_container">
        <ul id="contents1" class="tier_tabs_content">
            <?php
            $IsFirst = true;
            foreach ($Rewards as $Reward) {
                include("tiered-spender-reward-items.php");
                $IsFirst = false;
            }
            ?>
        </ul>
    </div>
</div>