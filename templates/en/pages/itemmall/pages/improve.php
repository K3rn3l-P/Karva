<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Shop</a> &rarr; <i><?= htmlspecialchars($title) ?></i></span>
	</div>
    <div class="page-body border_box self_clear">
		<!--BEGIN CONTENT-->		
		<div id="page-settings" class="mainbox page page-settings">
			<div class="mainbox-inner page-inner">
				<div class="mainbox-header page-header border_box">
					<div style="margin: 15px 0 0 30px;float: right;">
						<a class="nice_button" href="/?p=tiered" style="margin-right:10px;">Tiered Spender</a>
						<a class="nice_button" style="margin-left:10px; cursor:default;">Your Points: <?= htmlspecialchars($Point) ?> <?= htmlspecialchars($currencyCode) ?> </a>
						<a class="nice_button" href="/?p=billing" style="margin-right:10px;">BUY SP</a>
						<?php if ($IsGM) : ?>
							<a class='display_button' href='/?p=itemmall&admin'>Add Product</a>
						<?php endif ?>
					</div>
				</div>
				<section id="store">
					<section class="item_group"> 
						<?php
						$productId = filter_var($_GET["improve"], FILTER_VALIDATE_INT);
						if ($productId !== false && $productId > 0) {
							$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.products WHERE id = :productId");
							$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
							$stmt->execute();
							$product = $stmt->fetch(PDO::FETCH_ASSOC);
							if ($product) {
								include("modules/improve-main.php");
							}
						}
						?>	

					</section>
				</section>
			</div>
		</div>

		<!--END CONTENT -->
    </div>
</div>
