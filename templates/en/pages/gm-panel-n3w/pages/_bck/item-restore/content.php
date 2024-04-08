<?php
function getBrokeType($byItemID) {
    $type = floor($byItemID / 1000);
    switch ($type) {
        case 95:
            return "Enchant";
        case 30:
            return "Linking";
        case 0:
            return "Extraction";
        default:
            return "Unknown";
    }
}
?>
<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= htmlspecialchars($subpages[$subpage]["Title"]) ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

        <!-- begin content -->
        <div class="node format">
            <?php include("modules/list.php") ?>
        </div>
        <!-- end content -->  

    </div>
</div>
