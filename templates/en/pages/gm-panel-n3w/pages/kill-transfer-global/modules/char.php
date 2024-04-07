<tr>
	<td><?= $Char["CharID"] ?></td>
	<td><?= $Char["CharName"] ?></td>
	<td><div class="<?= $job_icon[$Char["Job"]] ?>"></div></td>
	<td><?= $Char["K1"] ?></td>
	<td><?= $Char["K2"] ?></td>
	<td><?= "<a onclick='TransferTo($Char[CharID])'>Transfer</a>" ?></td>
</tr>