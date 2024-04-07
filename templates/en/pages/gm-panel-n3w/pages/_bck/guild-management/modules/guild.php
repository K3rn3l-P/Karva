<?php 
$FactionIcon = ($Guild["Country"]) ? "faction-dark" : "faction-light";
?>
<tr>
	<td><?= $Guild["Rank"] ?></td>
	<td><div class='faction_icon <?= $FactionIcon ?>'></div></td>
	<td><?= $Guild["GuildName"] ?></td>
	<td><?= $Guild["MasterName"] ?></td>
	<td><?= $Guild["GuildPoint"] ?></td>
	<td><a onclick="Rename(<?= $Guild["GuildID"] ?>)">Rename</a></td>
	<td><a onclick="ChangeLeader(<?= $Guild["GuildID"] ?>)">Change leader</a></td>
</tr>