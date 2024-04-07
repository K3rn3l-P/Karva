<section class="sidebox_topvoters topvoter sidebox">
	<h4 class="sidebox_title border_box">
		<i>Weekly STARS Collectors</i>
		<div class="topvoter_desc"><a href="/?p=stars">VIEW COMPLETE LIST</a></div>
	</h4>
    <div class="sidebox_body border_box">
		<?php
		$query = "SELECT TOP 3 C.CharName, IL.Stars FROM PS_GameData.dbo.Chars IL
				LEFT JOIN PS_GameData.dbo.Chars C ON IL.CharID=C.CharID
				ORDER BY IL.Stars DESC";
		$odbcResult = odbc_exec($odbcConn, $query);
		$index = 1;
		while($item = odbc_fetch_array($odbcResult)) {
			include("row-item.php");
			$index++;
		}
		?>
		<center>
                <div class="topvoter_info">Stars Rewards are delivered every week to Top 100 Players. For more informations check <a href='/?p=stars-reward'>here!</a></div>
		</center>
     </div>
</section>