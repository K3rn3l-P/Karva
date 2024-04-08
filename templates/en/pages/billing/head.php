<?php
if (!$UserUID) {
	header("location:/");
	exit;
}
?>
<title><?= $ServerName ?> | Billing</title>

<?php include_once("$TemplateUrl/modules/head.php") ?>
