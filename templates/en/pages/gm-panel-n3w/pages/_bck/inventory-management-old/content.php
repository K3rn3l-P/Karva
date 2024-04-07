<?php
$CharName = isset($_GET["name"]) ? $_GET["name"] : "";
if ($CharName) {
	$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.Chars WHERE CharName='{$CharName}'");
	$Char = (odbc_num_rows($SqlRes)) ? odbc_fetch_array($SqlRes) : "";	
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