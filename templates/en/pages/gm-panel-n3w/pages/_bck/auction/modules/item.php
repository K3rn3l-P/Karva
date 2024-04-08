<?php
$minMoney = castToUInt($row["MinMoney"]);
$minMoney = number_format($minMoney, 0, '.', ' ');

$directMoney = castToUInt($row["DirectMoney"]);
$directMoney = number_format($directMoney, 0, '.', ' ');

$tenderMoney = castToUInt($row["TenderCharID"]);
$tenderMoney = number_format($tenderMoney, 0, '.', ' ');

$endDate = date("M d H:i", strtotime($row["EndDate"]));
$result = "<b class='dark-blue'>Active</b>";
if ($row["Del"]) {
	if ($row["Result"] === null) {
		$result = "<p class='indianred'>Finished (<b>Unknown</b>)</p>";
	} else {
		$result = $row["Result"] 
						? "<p class='lightgreen'>Finished (<b>Sold</b>)</p>"
						: "<p class='red'>Finished (<b>Not sold</b>)</p>";
	}
}

?>
<tr>
	<td class="marketid-column">
		<?= number_format($row["MarketID"], 0, '.', ' '); ?>
	</td>
	<td class="userid-column">
		<a href="/?p=gm-panel-n3w&sp=user-search&UserUID=<?= $row["UserUID"] ?>">
			<b><?= $row["UserID"] ?></b>
		</a>
	</td>
	<td class="charname-column">
		<a href="/?p=gm-panel-n3w&sp=user-search&CharID=<?= $row["CharID"] ?>">
			<b><?= $row["CharName"] ?></b>
		</a>
	</td>
	<td class="item-column" title="<?= $row["ItemID"] ?>">
		<b><?= $row["ItemName"] ?></b>
		<br />
		<?= $row["ItemID"] ?>
	</td>
	<td class="count-column"><?= $row["Count"] ?></td>
	<td class="iteminfo-column"><?= getGems($row["Gem1"], $row["Gem2"], $row["Gem3"], $row["Gem4"], $row["Gem5"], $row["Gem6"]) . getCraftname($row["Craftname"]) ?></td>
	<td class="minmoney-column"><?= $minMoney ?></td>
	<td class="buymoney-column"><?= $directMoney ?></td>
	<td class="bet-column">
		<?php if ($row["TenderCharID"]) : ?>
			Bet <?= $tenderMoney ?> by <br />
			<a href="/?p=gm-panel-n3w&sp=user-search&CharID=<?= $row["TenderCharID"] ?>">
				<?= $row["TenderCharName"] ?>
			</a>
		<?php endif ?>
	</td>
	<td class="enddate-column"><?= $endDate ?></td>
	<td class="status-column">
		<?= $result ?>
	</td>
</tr>