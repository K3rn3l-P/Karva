<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<style>
			#control {
				border: 1px solid #000;
				float: left;
				margin-left: 10px;
				font-size: 10px;
				margin-top: 10px;
			}

			#control td {
				border: 1px solid #000;
				padding: 5px;
				font-weight: 600;
			}
		</style>

		<table id='control'>
			<tr>
				<th>Killer CharName</th>
				<th>Level</th>
				<th>MapName</th>
				<th>Target CharName</th>
				<th>ActionTime</th>
			</tr>
			<?php
			$query = $conn->prepare('SELECT * CharName, CharLevel, MapID, TargetCharName, KillDate FROM PS_GameLog.dbo.OwnKill ORDER BY KillDate DESC');
			$query->execute();
			while ($kills = $query->fetch(PDO::FETCH_NUM)) {
				
				

				echo '<tr><td>' . $kills[1] . '</td><td>' . $kills[2] . '</td><td>' . $kills[3] .  '</td><td>' . $kills[4] . '</td><td>' . $kills[5] . '</td></tr>';
			}
			?>
		</table>
		<!-- end content -->	

    </div>
</div>