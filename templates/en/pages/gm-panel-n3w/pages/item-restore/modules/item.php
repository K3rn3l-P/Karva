<tr>
	<td><?= $Item["UserUID"] ?></td>
	<td><?= $Item["UserID"] ?></td>
	<td><?= $Item["CharName"] ?></td>
	<td><?= $Item["ItemID"] ?></td>
	<td><?= date( 'm-d H:i', strtotime($Item["DT"])) ?></td>
	
	<td><?= GetBrokeType($Item["ByItem"]) ?></td>
	<td><?= $Item["Res"] ? "Restored" : "<a href='$TemplateUrl/actions/gm-panel-n3w/item-restore.php?id=$Item[ID]'>Restore</a>" ?></td>
</tr>