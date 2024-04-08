<?php
$SpenderID = isset($_GET["id"]) && is_numeric($_GET["id"]) ? $_GET["id"] : 0;
$query = $pdo->prepare("SELECT *, DATEDIFF(DAY, CURRENT_TIMESTAMP, EndDate) AS [Days] FROM PS_WebSite.dbo.Tiered_Spender WHERE ID=:spender_id AND CURRENT_TIMESTAMP BETWEEN StartDate AND EndDate");
$query->bindParam(":spender_id", $SpenderID, PDO::PARAM_INT);
$query->execute();
$TS = $query->fetch(PDO::FETCH_ASSOC);
?>
<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>Rewards</a> &rarr; <i>Tiered Spender</i></span>
    </div>
    <div class="page-body border_box self_clear">
        <div class="block-head">
            <h1 class="title story-title"><?= $TS ? htmlspecialchars($TS["Name"]) : "Tiered Spender" ?></h1>
        </div>
        <div class="block-body" style="background-position: -760px -115px;">
            <div class="block-content" style="background-position: 0 -115px;">
                <div class="tabs primary">
                    <ul class="menu">
                        <?php include("modules/tiered-spender-list.php") ?>
                    </ul>
                </div>
                <div class="clear"></div>
                <!-- begin content -->
                <?php if ($TS) include("modules/tiered-spender.php") ?>
                <!-- end content -->
            </div>
        </div>
        <div class="block-foot"></div>
    </div>
</div>
