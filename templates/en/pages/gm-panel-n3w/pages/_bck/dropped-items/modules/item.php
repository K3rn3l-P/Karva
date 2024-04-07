<tr>
	<td><?= $Item["Text1"], " (", $Item["Value2"], ")" ?></td>
	<td><?= date( 'm-d H:i', strtotime($Item["ActionTime"])) ?></td>
	<td><?= $Item["Text2"] ?></td>
	<td><?= $Item["Text4"] ?></td>
	<td><?= "<a href='$TemplateUrl/actions/gm-panel-n3w/restore-dropped-item.php?id=$Item[row]'>Restore</a>" ?></td>
</tr>