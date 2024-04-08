<table class="nice_table">
	<tr>
		<td>ItemName</td>
		<td align="center">Count</td>
		<td align="center">Cost</td>
		<td align="center"></td>
	</tr>

    <?php
    $totalcount = 0;
    $total = 0;
    foreach ($_SESSION["products"] as $productId => $productCount) {
		include("cart-product.php");
    }
    ?>
	<tr class="text-bold text-white">
		<td>Total</td>
		<td align="center"><?= $totalcount ?></td>
		<td align="center"><?= $total ?></td>
		<td></td>
	</tr>
</table>



<div class="news_pagi border_box self_clear">										
	<div class="news_pagi-right">
		<a href="/?p=itemmall" class="nice_button" style="margin-right:10px;">Continue Shopping</a>
		<?php
		if ($Point < $total) {
			echo "<a class='nice_button' disabled>Not enough $currencyCode</a>";
		} else {
			echo "<a href='$TemplateUrl/actions/itemmall/buy-all.php' class='nice_button'>Buy All</a>";
		}
		?>
	</div>
</div>