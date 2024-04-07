<div class="topvoter_row">
	<div class="topvoter_col col_rank"><?= $index ?></div>
	<div class="topvoter_col col_name">
		<?= $item["CharName"] ?>
	</div>
	<div class="topvoter_col col_vote">
		<i><?= number_format($item["Stars"], 0, '.', ' ') ?></i> <img alt="" src="<?= $AssetUrl ?>images/icons/stars.png" width="" height="">
	</div>
</div>