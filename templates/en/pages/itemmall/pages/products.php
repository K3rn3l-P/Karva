<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Shop</a> &rarr; <i><?= $title ?></i></span>
	</div>
    <div class="page-body border_box self_clear">
		<!--BEGIN CONTENT-->
		<div id="page-settings" class="mainbox page page-settings">
			<div class="mainbox-inner page-inner">
				<div class="mainbox-header page-header border_box">
					<div style="margin: 15px 0 0 30px;float: right;">
						<a class="nice_button" href="/?p=tiered" style="margin-right:10px;">Tiered Spender</a>
						<a class="nice_button" style="margin-left:10px; cursor:default;">Your Points: <?= $Point, " ", $currencyCode ?> </a>
						<a class="nice_button" href="/?p=billing" style="margin-right:10px;">BUY SP</a>
						<?php if ($IsGM) : ?>
							<a class='display_button' href='/?p=itemmall&admin'>Add Product</a>
						<?php endif ?>
					</div>
				</div>
				<section id="store">
					<section class="item_group">
						<?php
						$results = $conn->prepare("SELECT * FROM PS_WebSite.dbo.products WHERE product_indx = ? ORDER BY id ASC");
						$results->bindValue(1, $category, PDO::PARAM_INT);
						$results->execute();
						while ($product = $results->fetch(PDO::FETCH_NUM)) {
							include("modules/product.php");
						}
						?>				

					</section>
				</section>
			</div>
		</div>
		<div class="tiered-banner">
			
		</div>
		<script type="text/javascript">
			function subtractQty(n){
				if(document.getElementById("product_qty"+ n).value - 1 < 1)
					return;
				else
					 document.getElementById("product_qty"+ n).value--;
			}
			function addQty(n){
				if (document.getElementById("product_qty"+ n).value + 1 > 200)
					return;
				document.getElementById("product_qty"+ n).value++;
			}
		</script>

		<!--END CONTENT -->

	</div>
	
	
	
</div>