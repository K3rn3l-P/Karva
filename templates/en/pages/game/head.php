<?php
$subpages = array(
	"about"
	,"classes"
	,"combat"
	,"features"
	,"races"
	,"rules"
	,"story"
	,"system-requirements"
);
// Get current subpage
$subpage = isset($_GET["sp"]) && in_array($_GET["sp"], $subpages, true) 
			? $_GET["sp"]
			: $subpages[0];
?>
<?php include_once("$PageUrl/pages/$subpage/head.php") ?>
<link type="text/css" rel="stylesheet" href="<?= $PageUrl ?>css/game.css"/>
