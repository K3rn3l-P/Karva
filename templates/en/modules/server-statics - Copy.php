<script>
				var serverTime = new Date('<?= date('Y-M-d H:i:s', strtotime("now") - 00 * 00) ?>');
				document.getElementById("time").innerHTML = serverTime.toLocaleTimeString();
				setInterval(function() {
					serverTime.setSeconds( serverTime.getSeconds() + 1);
					document.getElementById("time").innerHTML = serverTime.toLocaleTimeString();
				}, 1000);
</script>


<section id="sidebox_status" class="sidebox">
	<h4 class="sidebox_title border_box">
		<i>Server Information</i>
		<div class="topvoter_desc"><a href="/?p=game&sp=about">View More Info</a></div>
	</h4>
	<div class="sidebox_body border_box">
		<div id="realm_1" class="realm_1 realm_holder wotlk online lastrow">
			<div class="realm_row row-1 border_box">
				
				
				
				

<div class="realm_row row-1 border_box">
				
		<style>
.not-active {
   pointer-events: none;
   cursor: default;
}
</style>							
<iframe class="not-active" src="https://free.timeanddate.com/clock/i9av7mi7/n215/tlit/fc009fff/tc000/pc009fff/ftb/tt0/th1/ta1" frameborder="0" width="299" height="18"></iframe>

				
				<br>
				<span class="r_name overflow_ellipsis">Release Date: </span>
				<span style="color:orange" class="r_status">29.1.2021</span>
				<br>
				
				<span class="r_name overflow_ellipsis">Server Status: </span>
				<span style="color:#00FF00" class="r_status">ONLINE</span>
				<br>				
					
				<span class="r_name overflow_ellipsis">Game Version: </span>
				<span class="r_status">Episode 5.4</span>
				<br>
				<span class="r_name overflow_ellipsis">EXP Rate: </span>
				<span class="r_status">x150</span>
				<br>
				<span class="r_name overflow_ellipsis">Kill Rate: </span>
				<span class="r_status">x1</span>
				
				
				</div>


			
		
				
				
				
				<?php
				$factionCount = array(
					0 => 1,
					1 => 1
				);
				// Get faction count of players
				$query = "SELECT UMG.Country, COUNT(1) AS [Cnt] FROM PS_GameData.dbo.Chars C 
						LEFT JOIN PS_GameData.dbo.UserMaxGrow UMG ON UMG.UserUID=C.UserUID
						WHERE C.LoginStatus=1
						GROUP BY UMG.Country";
				$odbcResult = odbc_exec($odbcConn, $query);
				while ($item = odbc_fetch_array($odbcResult)) {
					$country = $item["Country"];
					$factionCount[$country] = $item["Cnt"] + 1;
				}
				$total = $factionCount[0] + $factionCount[1];
				$factionPerc = array(
					0 => intval($factionCount[0] / $total * 100),
					1 => intval($factionCount[1] / $total * 100)
				);
				
				
				
				$FakeCount = 0;
				// Total population
				$query = "SELECT COUNT(1) AS [Cnt] FROM PS_GameData.dbo.UserMaxGrow";
				$odbcResult = odbc_exec($odbcConn, $query);
				$totalUsers = 0;
				while ($item = odbc_fetch_array($odbcResult))
					$totalUsers = $item["Cnt"];
				
				// Now
				$SqlRes = odbc_exec($odbcConn, "SELECT COUNT(1) AS [Cnt] FROM PS_UserData.dbo.Users_Master WHERE Leave=1");
				$CurrCount = odbc_result($SqlRes, "Cnt") + $FakeCount;
				
				
				
				// Total today
				$SqlRes = odbc_exec($odbcConn, "SELECT COUNT(1) AS [Cnt] FROM PS_UserData.dbo.Users_Master WHERE CAST([LeaveDate] AS DATE)=CAST(CURRENT_TIMESTAMP AS DATE)");
				$TodayCount = odbc_result($SqlRes, "Cnt") + $FakeCount;
				?> 
				
			</div>
			
			
			
			<div class="realm_row row-2 border_box">
			<center>This graph shows faction online statistics</center>
			<div class="r_bar border_box">
			
					<div class="r_bar-inner">
					
						<div class="" data-tip="Union of Fury" style="float: left; z-index: 100; min-width: 50px; width: <?= $factionPerc[1] ?>%; height: 100%;"></div>
						<div class="r_bar-fill bar-h " style="min-width: 50px; width: <?= $factionPerc[1] + 5 ?>%; text-align: left;"><span><?= $factionPerc[1] ?>%</span></div>
						<div class="r_bar-fill bar-a index" style="min-width: 50px; width: <?= $factionPerc[0] ?>%; text-align: right;"><span><?= $factionPerc[0] ?>%</span></div>
						<div class="" data-tip="Alliance of Light" style="float: right; z-index: 100; min-width: 50px; width: <?= $factionPerc[0] ?>%; height: 100%;"></div>
					</div>
				</div>
			</div>
			
			
			<div class="text-center" style="margin-top: 8px;">	
				<div class="player_count_additional_stats_value"><?= $CurrCount ?>/1000</div>
				<div class="player_count_additional_stats_title">Players Online</div><br>
			<div class="player_count_additional_stats_value"><?= $TodayCount ?></div>
				<div class="player_count_additional_stats_title">Total entered today</div><br><p style="background-color:powderblue;">NO FAKE STATISTICS!</p>
			</div>
			
			
		
			
		</div>
	</div>
	
	
        
		
        

	
</section>