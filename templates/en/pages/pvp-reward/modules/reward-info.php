<?php 
$Redeem = "<img src='images/lock.png' title='You need more kills for redeem this reward' />";
if ($Kills > $Reward["Kills"]) {
	if ($Reward["UserUID"]) {
		$Redeem = "Redeemed";
	} else if (!$CanRedeem) {
		$Redeem = "<img src='images/lock.png' title='You cant redeem now' />";
	} else {
		$Redeem = $isFirst ? "<a class='pointer-link' onclick='ShowItems($Reward[ID])'>Redeem</a>" : "<img src='images/unlock.png' title='You must redeem previous reward' />";
		$isFirst = false;
	}
}
?>
<tr>
	<td><?= $Reward["Kills"] ?></td>
	<td><img src="images/rewards/<?= $Reward["Icon"] ?>" /></td>
	<td><?= $Reward["SP"] ?></td>

	<td><?= $Redeem ?></td>
</tr>
<?php include("reward-items.php") ?>