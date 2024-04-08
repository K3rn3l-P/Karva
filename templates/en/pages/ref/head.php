<?php
if (!$UserUID) {
	header("location:/?p=login");
	exit;
}
?>
<title><?= $ServerName ?> |  Referrer System</title>
<?php include_once("$TemplateUrl/modules/head.php") ?>
<link type="text/css" rel="stylesheet" href="<?= $PageUrl ?>css/style.css"/>
<link type="text/css" rel="stylesheet" href="<?= $PageUrl ?>css/bootstrap-changed.css"/>
