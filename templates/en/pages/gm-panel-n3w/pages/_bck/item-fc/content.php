<?php
$CharName = isset($_GET["name"]) ? $_GET["name"] : "";
if ($CharName) {
	$stmt = $conn->prepare("SELECT CharID FROM PS_GameData.dbo.Chars WHERE CharName=?");
	$stmt->bindValue(1, $CharName, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$CharID = $row ? $row['CharID'] : '';
}
?>  
<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<div class="node format">
			<?php include("modules/search.php") ?>
			<?php if ($CharName) include($Char ? "modules/list.php" : "modules/notexists.php") ?>
		</div>
		<!-- end content -->

    </div>
</div>