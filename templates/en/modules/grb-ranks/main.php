<section class="sidebox_topvoters topvoter sidebox">
	<h4 class="sidebox_title border_box">
		<i>GRB RANKING</i>
		<div class="topvoter_desc"><a href="/?p=guilds">VIEW COMPLETE GUILDS</a></div>
	</h4>
    <div class="sidebox_body border_box">
		<?php
		$query = "SELECT TOP 5 GuildName, GuildPoint FROM PS_GameData.dbo.Guilds WHERE Del=0 ORDER BY GuildPoint DESC";
		$odbcResult = odbc_exec($odbcConn, $query);
		$index = 1;
		while($item = odbc_fetch_array($odbcResult)) {
			include("row-item.php");
			$index++;
		}
		?>
		<center>
                <div class="topvoter_info">Weekly GRB rewards will be given to Top 3 guilds. For more informations check <a href='/?p=grb-reward'>here!</a></div>
		</center>
     </div>
</section>