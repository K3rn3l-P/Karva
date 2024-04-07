<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Billing</a> &rarr; <i>Purchase History</i> </span>
	</div>
    <div class="page-body border_box self_clear">
		<?php $UserUID ? include(isset($_GET["logs"]) ? "pages/logs.php" : "pages/main.php") : include("pages/not-logged.php") ?>
    </div>
</div>