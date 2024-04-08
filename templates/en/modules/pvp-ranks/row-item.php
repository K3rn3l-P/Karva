<div class="topvoter_row">
	<div class="topvoter_col col_rank"><?php echo $index; ?></div>
	<div class="topvoter_col col_name">
		<?php echo htmlspecialchars($item["CharName"]); ?>
	</div>
	<div class="topvoter_col col_vote">
		<i><?php echo number_format($item["K1"], 0, '.', ' '); ?></i> kills
	</div>
</div>
