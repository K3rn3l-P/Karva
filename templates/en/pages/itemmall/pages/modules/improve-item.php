<div class="store_item" id="item_<?= $item["id"] ?>">
		<input type="hidden" name="id" value="<?= $item["id"] ?>" />
		
		<a class="item_name"><?= $item["ItemName"], " - ", $item["Reqlevel"], "lv" ?></a>
		
		<?php if (!$item["Slot"]) : ?>
			<div class="item-gems">			
				<span>Item doesn't have slots</span>		
			</div>		
		<?php endif ?>
		
		<?php for ($g = 1; $g <= $item["Slot"]; $g++) : ?>
			<div class="item-gems">			
				<span>Slot <?= $g ?></span>
				<select name="item[<?= $item["id"] ?>][gem<?= $g ?>]" style="width:300px;" <?= $item["Gem$g"] ? "disabled" : "" ?> 
						onchange="gemChanged(<?= $item["id"] ?>, <?= $g ?>, this)">
					<option value="0">No lapis</option>
					<?php 
					if ($item["Gem$g"]) {
						$gemId = $item["Gem$g"];
						$gem = $Gems[$gemId];
						echo "<option selected>$gem[ItemName] - Free (Native)</option>";
					} else {
						foreach ($GemPrice as $gemId => $price) {
							$gem = $Gems[$gemId];
							if (canLinkLapis($gem, $item["Type"]))
								echo "<option value='$gemId'>$gem[ItemName] - $price $currencyCode</option>";
						}
					}					
					?>					
				</select>
			</div>
		<?php endfor ?>
		
				
		<?php if ($item["Reqluc"] != 0) : ?>
			<section class="store_buttons">
				<span>Enchant</span>
				<input type="button" onclick="javascript: subtractEnchant(<?= $item["id"] ?>);" value="-" class="quantity">
				<input id="item-<?= $item["id"] ?>-enchant" class="shop-counter" 
					   type="text" value="<?= $item["Enchant"] ?>" min="<?= $item["Enchant"] ?>" max="<?= max(array_keys($EnchantPrice["armor"])) ?>" name="item[<?= $item["id"] ?>][enchant]" readonly >
				<input type="button" onclick="javascript: addEnchant(<?= $item["id"] ?>);" value="+" class="quantity">
				<br />
				Next level: 
				<a id="item-<?= $item["id"] ?>-enchant-price">
					<?= array_key_exists($item["Enchant"] + 1, $EnchantPrice[$item["equipType"]]) ? ($EnchantPrice[$item["equipType"]][$item["Enchant"] + 1] . " " . $currencyCode) : "Not available" ?>
				</a>
			</section>
		<?php endif ?>
		
		<div class="clear"></div>
</div>

<script type="text/javascript">
	var item = {
		id: <?= $item["id"] ?>,
		type: "<?= getItemType($item["Type"]) ?>",
		Slot: <?= $item["Slot"] ?>,
		Enchantable: <?= $item["Reqluc"] > 0 ? "true" : "false" ?>,
		Enchant: <?= $item["Enchant"] ?>,
		MinEnchant: <?= $item["Enchant"] ?>,	
		Gems: [
			0, "<?= $item["Gem1"] ?>", "<?= $item["Gem2"] ?>", "<?= $item["Gem3"] ?>", "<?= $item["Gem4"] ?>", "<?= $item["Gem5"] ?>", "<?= $item["Gem6"] ?>",
		]
	};
	items[<?= $item["id"] ?>] = item;	
</script>