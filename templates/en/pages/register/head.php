<?php
if ($UserUID) {
	header("location:/?p=ucp");
	exit;
}
?>
<title><?= $ServerName ?> | Create Account</title>
<?php include_once("$TemplateUrl/modules/head.php") ?>
