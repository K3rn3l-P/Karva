<section class="sidebox_topvoters topvoter sidebox">
	<h4 class="sidebox_title border_box">
		<i>WEEKLY PvP Ranking</i>
		<div class="topvoter_desc"><a href="/?p=ranks&type=1">VIEW COMPLETE RANKING</a></div>
	</h4>
    <div class="sidebox_body border_box">
		<?php
		$query = "SELECT TOP 3 c.CharName,  ISNULL(WK,0) AS WK
				 FROM (SELECT TOP 3 COUNT(1) AS [WK], CharID FROM PS_GameLog.dbo.Kills WHERE DT>DATEADD(WEEK,-1,CURRENT_TIMESTAMP) GROUP BY CharID ORDER BY WK DESC) W
				 LEFT JOIN PS_GameData.dbo.Chars C ON W.CharID=C.CharID
				 WHERE c.Del=0
				 ORDER BY WK DESC";
		$odbcResult = odbc_exec($odbcConn, $query);
		$index = 1;
		while($item = odbc_fetch_array($odbcResult)) {
			include("row-item.php");
			$index++;
		}
		?>
		<center>
			<div class="topvoter_info">Fight your opponents in pvp battles and claim PvP Rewards. For more informations check <a href='/?p=pvp-reward'>here!</a></div>
		</center>
     </div>
</section>