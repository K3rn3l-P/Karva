<?php
if ($UserUID) {
	header("location:/?p=ucp");
	exit;
}
?>
<title><?= $ServerName ?> | Login</title>
<?php include_once("$TemplateUrl/modules/head.php") ?>
