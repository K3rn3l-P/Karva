<tr>
	<td><?= $row[1] ?></td>
	<td class="sta"><?= $cat ?></td>
	<td><?= $row1[0] ?></td>
	<td class="ogg">
		<a href="/?p=support&panel=<?= $row[1] ?>">
			<?= $row[5] ?>
			<img id="img<?= $row[1] ?>" class="<?= $row[7] ?>" src="<?= $AssetUrl ?>images/support/<?= $row[7] ?>-<?= $row[8] ?>.png">
		</a>
	</td>
	<td class="dat"><?= $date ?></td>
</tr>