<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Shop</a> &rarr; <i>My cart</i></span>
	</div>
    <div class="page-body border_box self_clear">
		<?php include(isset($_SESSION["products"]) && count($_SESSION["products"]) ? "modules/cart-products.php" : "modules/cart-empty.php") ?>
    </div>
</div>