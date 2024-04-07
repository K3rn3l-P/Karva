<?php
$result = odbc_exec($odbcConn, "SELECT product_name, price FROM PS_WebSite.dbo.products WHERE id=$productId");
//die($productId);
if (!odbc_num_rows($result))
	return;

$product = odbc_fetch_array($result);
$cost = $product["price"] * $productCount;

$totalcount = $totalcount + $productCount;
$total += $cost;
?>
<tr>
	<td><?= $product["product_name"] ?></td>
	<td align="center"><?= $productCount ?></td>
	<td align="center"><?= $cost ?></td>
	<td align="center">
		<a href="<?= $TemplateUrl ?>actions/itemmall/cart-del.php?id=<?= $productId ?>">
			<img src="<?= $AssetUrl ?>images/icon_trash.gif">
		</a>
	</td>
</tr>
	