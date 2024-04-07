<div class="topvoter_row">
	<div class="topvoter_col col_rank"><?= $index ?></div>
	<div class="topvoter_col col_name">
		<?= $item["GuildName"] ?>
	</div>
	<div class="topvoter_col col_vote">
		<i><?= number_format($item["GuildPoint"], 0, '.', ' ') ?></i> points
	</div>
</div>