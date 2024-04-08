<tr>
	<td><?= $Item["Bag"] ?></td>
	<td><?= $Item["Slot"] ?></td>
	<td><?= $Item["ItemName"], " (", $Item["ItemID"], ")" ?></td>
	<td><?= $Item["Count"] ?></td>
	<td><?= $Item["Gem1"], ", ", $Item["Gem2"], ", ", $Item["Gem3"], ", ", $Item["Gem4"], ", ", $Item["Gem5"], ", ", $Item["Gem6"] ?></td>
	<td><?= $Item["Craftname"] ?></td>
	<td><?= "<a href='$TemplateUrl/actions/gm-panel-n3w/inventory-management/itemfc.php?cid=$Item[CharID]&item=$Item[ItemUID]'>FC</a>" ?></td>
</tr>