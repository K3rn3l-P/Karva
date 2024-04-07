<?php
// Get current kills
$Kills = 0;
$CanRedeem = true;
if ($UserUID) {
	$SqlRes = odbc_exec($odbcConn, "SELECT ISNULL(MAX(K1),0) AS [K1], ISNULL(DATEDIFF(HOUR, MAX(L.DT), CURRENT_TIMESTAMP), 999) AS [DateDiff]
									FROM PS_GameData.dbo.Chars C
									LEFT JOIN PS_WebSite.dbo.PvPReward_User_Log AS L ON L.UserUID=$UserUID
									WHERE C.UserUID=$UserUID");
	$Kills = odbc_result($SqlRes, "K1");
	$DateDiff = odbc_result($SqlRes, "DateDiff");
	$CanRedeem = $DateDiff >= 12;
	$remain = 12 - $DateDiff;
}
?>

<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Rewards</a> &rarr; <i >PvP Reward</i></span>
	</div>
	
    <div class="page-body border_box self_clear">
	
<div style="text-align:right;">
<a class="nice_button nice_active support-button" href="./?p=pvp-reward">PvP Rewards</a>
<a class="nice_button support-button" href="/?p=grb-reward">GRB Rewards</a>
</div>
<br>

		<h1 class="red center">
			<?= $CanRedeem ? "" : "You can redeeem next after $remain hours" ?>
		</h1>
			
		
		<table class="table-bordered  center" style="width: 100%">
		<tr>
			<th>Kills</th>
			<th>Icon</th>
			<th>SP Reward</th>
			
			<th>Redeem</th>
		</tr>
		<?php
		$SqlRes = odbc_exec($odbcConn, "SELECT R.*, L.UserUID FROM PS_WebSite.dbo.PvPReward AS [R]
									LEFT JOIN PS_WebSite.dbo.PvPReward_User_Log AS [L] ON L.RewardID=R.ID AND L.UserUID=$UserUID
									ORDER BY [Kills]");
		$isFirst = true;
		while ($Reward = odbc_fetch_array($SqlRes)) {
			include("modules/reward-info.php");
		}
		?>
		</table>
		
    </div>
	
</div>