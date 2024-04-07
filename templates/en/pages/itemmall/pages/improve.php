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
						$productId = is_numeric($_GET["improve"]) ? $_GET["improve"] : 0;
						$result = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.products WHERE id = $productId");
						if (odbc_num_rows($result)) {
							$product = odbc_fetch_array($result);
							include("modules/improve-main.php");
						}
						?>	

					</section>
				</section>
			</div>
		</div>

		<!--END CONTENT -->
    </div>
</div>