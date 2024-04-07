<div class="popup-redeem" id="redeem-<?= $Reward["ID"] ?>">
	<a onclick="HideItems(<?= $Reward["ID"] ?>)" class="right white pointer-link">Close</a>
	<?php
	$ItemsRes = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.PvPReward_Items WHERE RewardID=$Reward[ID] ORDER BY [ID]");
	while ($RewardItem = odbc_fetch_array($ItemsRes)) {
		include("reward-items-info.php");
	}
	?>
</div>