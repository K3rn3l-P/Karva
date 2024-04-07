<?php
switch ($product[7]) {
	case 0:
		$c = 'q1';
		break;
	case 1:
		$c = 'q2';
		break;
	case 2:
		$c = 'q3';
		break;
	case 3:
		$c = 'q4';
		break;
}
?>
<div class="store_item" id="item_<?= $product[0] ?>">
	<form id="addItem" method="post" action="<?= $TemplateUrl ?>actions/itemmall/buy-now.php">
		<input type="hidden" name="id" value="<?= $product[0] ?>" />
		<section class="store_buttons">
			<input type="button" onclick="javascript: subtractQty(<?= $product[0] ?>);" value="-" class="quantity">
			<input id="product_qty<?= $product[0] ?>" class="shop-counter" 
				   type="text" value="1" max="20" name="count" readonly >
			<input type="button" onclick="javascript: addQty(<?= $product[0] ?>);" value="+" class="quantity">

			<button class="nice_button dp-button" title="Buy now">
				<img src="<?= $AssetUrl ?>images/icons/coins.png" align="absmiddle">
				<span class="dp_price_value"><?= $product[5] ?></span> <?= $currencyCode ?>
			</button>
		</section>
		
		<img class="item_icon" src="<?= $AssetUrl, $product[4] ?>" align="absmiddle" />
		<a class="item_name <?= $c ?>"><?= $product[2] ?></a>
		<br>
		<?= $product[3] ?>
		<div class="clear"></div>
	</form>
</div>