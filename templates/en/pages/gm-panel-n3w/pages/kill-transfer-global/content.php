<?php
$Username = isset($_GET["name"]) ? $_GET["name"] : "";
$Chars = array();
if ($Username) {
	$stmt = $conn->prepare("SELECT * FROM PS_GameData.dbo.Chars WHERE UserID=? AND Del=0");
	$stmt->bindValue(1, $Username, PDO::PARAM_INT);
	$stmt->execute();
	while ($Arr = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$Chars[] = $Arr;
	}
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
		<?php if ($Username) include($Chars ? "modules/list.php" : "modules/notexists.php") ?>
		</div>
		<!-- end content -->	

    </div>
</div>

<script>
function TransferTo(CharID) {
	var Name = prompt("Enter the nickname of the character to which kills will be sent.");
	if (!Name) 
		return;
	window.location.href = "<?= $TemplateUrl ?>actions/gm-panel-n3w/kill-transfer-global.php?id=" + CharID + "&name=" + Name;
}
</script>