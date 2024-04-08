<?php
if ($UserUID) {
	header("location:$HomeUrl");
	exit;
}
?>
<title><?= $ServerName ?> | Password recovery</title>
<?php include_once("$TemplateUrl/modules/head.php") ?>
