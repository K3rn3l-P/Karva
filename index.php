<?php
// Загрузка конфигураций
include_once('config/config.php');

	if(isset($_GET["p"]) && $_GET['p'] == "billing") {
		if (!$UserUID) {
		header("location:/");
		exit;
		}
		include_once("$PageUrl/content.php");
		die();
	}

include_once("$TemplateUrl/modules/vote-check.php");


$TemplateName = "main";
?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once("$PageUrl/head.php") ?>
	

</head>
<body class="theme_havoc">

<?php $alerts = GetAlerts() ?>

<div id="popup_bg"></div>





<?php include_once("$TemplateUrl/modules/header.php") ?>
<?php include_once("$TemplateUrl/modules/slider/main.php") ?>

<?= $alerts ?>
<div class="main_b_holder">
	<div class="body_content">
		<?php include_once("$TemplateUrl/modules/membership/main.php") ?>
		
		<aside id="right" class="mainside">
			<?php include_once("$PageUrl/content.php") ?>
		</aside>

		<aside id="left" class="sidebar border_box">
			<?php include_once("$TemplateUrl/modules/login.php") ?>
			<?php include_once("$PageUrl/right.php") ?>
			<?php include_once("$TemplateUrl/modules/server-statics.php") ?>
			<?php include_once("$TemplateUrl/modules/pvp-ranks/main.php") ?>
			<?php include_once("$TemplateUrl/modules/grb-ranks/main.php") ?>
			<?php include_once("$TemplateUrl/modules/boss-timers/main.php") ?>
		
		  <div class="fb-page" data-href="https://www.facebook.com/shaiyaduff/" data-tabs="timeline" data-width="330" data-height="70" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/shaiyaduff/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/shaiyaduff/">Shaiya Duff!</a></blockquote></div>
		
	
		</aside>
		
		
        <div class="clear"></div>
    </div>
</div>

<?php include_once("$TemplateUrl/modules/footer.php") ?>
<script type="text/javascript" src="<?= $AssetUrl ?>js/jquery.fancybox.min.js"></script>

<script src='https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js'></script>
<script src='<?= $AssetUrl ?>js/spinners.min.js'></script>
<script src='<?= $AssetUrl ?>js/lightview.js'></script>

</body>
</html>






