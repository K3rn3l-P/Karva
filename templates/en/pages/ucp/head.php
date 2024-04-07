<?php
if (!$UserUID) {
	header("location:/?p=login");
	exit;
}
?>
<title><?= $ServerName ?> | User panel</title>
<?php include_once("$TemplateUrl/modules/head.php") ?>
<link type="text/css" rel="stylesheet" href="<?= $PageUrl ?>css/style.css"/>
