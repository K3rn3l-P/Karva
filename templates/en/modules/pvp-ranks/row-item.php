<div class="topvoter_row">
	<div class="topvoter_col col_rank"><?= $index ?></div>
	<div class="topvoter_col col_name">
		<?= $item["CharName"] ?>
	</div>
	<div class="topvoter_col col_vote">
		<i><?= number_format($item["K1"], 0, '.', ' ') ?></i> kills
	</div>
</div>