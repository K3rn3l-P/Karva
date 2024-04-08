<?php
$makeTime = date("Y M d H:i", strtotime($row["Maketime"]));
?>
<tr>
	<td class="charname-column <?= $charId || $userUid ? "hidden" : "" ?>">
		<?php if ($isWarehouse) : ?>
			<a href="/?p=gm-panel-n3w&sp=user-search&UserUID=<?= $row["UserUID"] ?>">
				<b><?= $row["UserID"] ?></b>
			</a>
		<?php else : ?>
			<a href="/?p=gm-panel&sp=user-search&CharID=<?= $row["CharID"] ?>">
				<b><?= $row["CharName"] ?></b>
			</a>
		<?php endif ?>
	</td>
	<td class="itemuid-column <?= $itemUid ? "hidden" : "" ?>"><?= $row["ItemUID"] ?></td>
	<td class="bag-column"><?= $isWarehouse ? "WH " . floor($row["Slot"] / 60) : $row["Bag"] ?></td>
	<td class="slot-column"><?= $row["Slot"] ?></td>
	<td class="item-column" title="<?= $row["ItemID"] ?>">
		<b class="<?= getSort($row["ReqDex"]) ?>"><?= $row["ItemName"] ?></b>
		<br />
		<?= $row["ItemID"] ?>
	</td>
	<td class="count-column"><?= $row["Count"] ?></td>
	<td class="iteminfo-column"><?= getCraftname($row["Craftname"]) ?></td>
	<td class="gems-column"><?= getGems($row) ?></td>
	<td class="maketime-column"><?= $makeTime ?></td>
	<td class="action-column">
		<?php if ($isWarehouse) : ?>
			<a href='<?= $TemplateUrl ?>/actions/gm-panel-n3w/warehouse-management/removeone.php?uid=<?= $row["UserUID"] ?>&item=<?= $row["ItemUID"] ?>'>Delete one</a><br />
			<a href='<?= $TemplateUrl ?>/actions/gm-panel-n3w/warehouse-management/removeall.php?uid=<?= $row["UserUID"] ?>&item=<?= $row["ItemUID"] ?>'>Delete all</a>
		<?php else : ?>
			<a href='<?= $TemplateUrl ?>/actions/gm-panel-n3w/inventory-management/removeone.php?cid=<?= $row["CharID"] ?>&item=<?= $row["ItemUID"] ?>'>Delete one</a><br />
			<a href='<?= $TemplateUrl ?>/actions/gm-panel-n3w/inventory-management/removeall.php?cid=<?= $row["CharID"] ?>&item=<?= $row["ItemUID"] ?>'>Delete all</a>
		<?php endif ?>
	</td>
</tr>