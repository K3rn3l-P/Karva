<?php
$result = odbc_exec($odbcConn, "SELECT * FROM PS_GameDefs.dbo.Items WHERE Type=30");
$Gems = array();
while ($gem = odbc_fetch_array($result)) {
	$typeId = $gem["TypeID"];
	$Gems[$typeId] = $gem;
}
?>

<script type="text/javascript">
	var items = [];
	var ProductPrice = <?= $product["price"] ?>;
	var EnchantCost = 0;
	var LinkingCost = 0;
	var TotalCost = 0;
	var MaxEnchantStep = {
		armor: <?= max(array_keys($EnchantPrice["armor"])) ?>,
		weapon: <?= max(array_keys($EnchantPrice["weapon"])) ?>
	};
	
	var EnchantPrice = {
		armor: <?= json_encode($EnchantPrice["armor"]) ?>,
		weapon: <?= json_encode($EnchantPrice["weapon"]) ?>,
	};
	
	var GemPrice = <?= json_encode($GemPrice) ?>;
	
	function subtractEnchant(id) {
		var item = items[id];
		if (item.Enchant <= item.MinEnchant)
			return;
		item.Enchant--;
		$("#item-" + id + "-enchant").val(item.Enchant);
		
		EnchantCost -= EnchantPrice[item.type][item.Enchant + 1];
		TotalCost = ProductPrice + EnchantCost + LinkingCost;
		$("#total-cost").html(TotalCost);
		
		var nextLevelPrice = EnchantPrice[item.type][item.Enchant + 1];
		$("#item-" + id + "-enchant-price").html(nextLevelPrice);
	}
	function addEnchant(id) {
		var item = items[id];
		if (item.Enchant >= MaxEnchantStep[item.type])
			return;
		item.Enchant++;
		$("#item-" + id + "-enchant").val(item.Enchant);
		
		EnchantCost += EnchantPrice[item.type][item.Enchant];
		TotalCost = ProductPrice + EnchantCost + LinkingCost;
		$("#total-cost").html(TotalCost);
		
		var nextLevelPrice = EnchantPrice[item.type][item.Enchant + 1] + " <?= $currencyCode ?>";
		if (item.Enchant == MaxEnchantStep[item.type])
			nextLevelPrice = "Max. enchant level reached!";
		$("#item-" + id + "-enchant-price").html(nextLevelPrice);
	}
	
	function gemChanged(id, index, elem) {
		var item = items[id];
		var oldGem = item.Gems[index];
		var newGem = $(elem).val();
		console.log(item);
		if (newGem > 0 && item.Gems.includes(newGem)) {
			$(elem).val(oldGem);
			alert("You can't select same gems for one item");
			return;
		}
		if (oldGem > 0)
			LinkingCost -= GemPrice[oldGem];
		if (newGem > 0)
			LinkingCost += GemPrice[newGem];
		
		item.Gems[index] = newGem;
		TotalCost = ProductPrice + EnchantCost + LinkingCost;
		$("#total-cost").html(TotalCost);
	}
</script>
		
<div class="store_item">
	<img class="item_icon" src="<?= $AssetUrl, $product["product_img_name"] ?>" align="absmiddle" />
	<a class="item_name"><?= $product["product_name"] ?></a>
	<br>
	<?= $product["product_desc"] ?>
	<div class="clear"></div>
</div>

<form method="post" action="<?= $TemplateUrl ?>actions/itemmall/buy-improved.php">
	<input type="hidden" name="product" value="<?= $productId ?>" />
	<?php
	$result = odbc_exec($odbcConn, "SELECT PI.*, I.*
									FROM PS_WebSite.dbo.products_buy [PI]
									LEFT JOIN PS_GameDefs.dbo.Items [I] ON [PI].ItemID=[I].ItemID
									WHERE product_code='$product[product_code]' AND CanImprove=1
									ORDER BY PI.ItemID");
	while ($item = odbc_fetch_array($result)) {
		$item["equipType"] = getItemType($item["Type"]);
		include("improve-item.php");
	}
	?>
	
	<p>Total cost: <a id="total-cost"><?= $product["price"] ?></a> <?= $currencyCode ?></p>
	<br />
	<button class="nice_button" title="Buy now">Buy now</button>
</form>

<script type="text/javascript">
	console.log();
</script>