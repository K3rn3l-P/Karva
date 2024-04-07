<tr>
	<td><?= $Item["Bag"] ?></td>
	<td><?= $Item["Slot"] ?></td>
	<td><?= $Item["ItemName"], " (", $Item["ItemID"], ")" ?></td>
	<td><?= $Item["Count"] ?></td>
	<td><?= $Item["Gem1"], ", ", $Item["Gem2"], ", ", $Item["Gem3"], ", ", $Item["Gem4"], ", ", $Item["Gem5"], ", ", $Item["Gem6"] ?></td>
	<td><?= $Item["Craftname"] ?></td>
	<td><?= "<a href='$TemplateUrl/actions/gm-panel-n3w/inventory-management/removeone.php?id=$Item[CharID]&uid=$Item[ItemUID]'>Delete one</a>" ?></td>
	<td><?= "<a href='$TemplateUrl/actions/gm-panel-n3w/inventory-management/removeall.php?id=$Item[CharID]&uid=$Item[ItemUID]'>Delete All</a>" ?></td>
</tr>