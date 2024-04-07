<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Community</a> &rarr; <i >Guilds</i></span>
	</div>
    <div class="page-body border_box self_clear">
		<section class="statistics_top_hk" style="display:block;">
			<div class="faction-header" style="margin:10px 0 15px 30px">				
				<div class="faction_icon faction-light left"></div>
				<h2 style="margin-left: 40px; color: #c8a73e;">Alliance of Light</h2>
			</div>
			<table class="nice_table" cellspacing="0" cellpadding="0">
				<tbody>
				<tr>
					<td width="10%" align="center">
						<div class="info-col col-1 overflow_ellipsis">
							<img alt="" src="<?= $AssetUrl ?>images/icons/award_star_bronze_1.png" width="" height=""> Rank
						</div>
					</td>
					<td width="20%" align="center">Guild Name</td>
					<td width="20%" align="center">Guild Master</td>
					<td width="15%" align="center">Total Player</td>
					<td width="15%" align="center">GuildPoints</td>
				</tr>
				<?
				$i = 1;
				$qGuild = $conn->prepare("SELECT TOP 50 * FROM PS_GameData.dbo.Guilds WHERE Del = 0 AND Country = 0 ORDER BY GuildPoint DESC");
				$qGuild->execute();
				while ($g = $qGuild->fetch(PDO::FETCH_NUM)) {
					$r = $i . 'th';
					switch ($i) {
						case 1:
							$r = '1st';
							break;
						case 2:
							$r = '2nd';
							break;
						case 3:
							$r = '3rd';
							break;
					}

					echo ' <tr>
								<td width="10%" align="center">' . $r . '</td>
								<td width="30%" align="center"><a>' . $g[2] . '</a></td>
								<td width="15%" align="center">' . $g[5] . '</td>
								<td width="15%" align="center">' . $g[7] . '</td>
								<td width="15%" align="center">' . $g[8] . '</td>
							</tr>';
					$i++;
				}
				?>
				</tbody>
			</table>
			
			<div class="faction-header" style="margin:20px 0 15px 30px">				
				<div class="faction_icon faction-dark left"></div>
				<h2 style="margin-left: 40px; color: #c8a73e;">Union of Fury</h2>
			</div>
			<table class="nice_table" cellspacing="0" cellpadding="0">
				<tbody>
				<tr>
					<td width="10%" align="center">
						<div class="info-col col-1 overflow_ellipsis">
							<img src="<?= $AssetUrl ?>images/icons/award_star_bronze_1.png" width="" height=""> Rank
						</div>
					</td>
					<td width="20%" align="center">Guild Name</td>
					<td width="20%" align="center">Guild Master</td>
					<td width="15%" align="center">Total Player</td>
					<td width="15%" align="center">GuildPoints</td>
				</tr>
				<?
				$i = 1;
				$qGuild = $conn->prepare("SELECT TOP 50 * FROM PS_GameData.dbo.Guilds WHERE Del = 0 AND Country = 1 ORDER BY GuildPoint DESC");
				$qGuild->execute();
				while ($g = $qGuild->fetch(PDO::FETCH_NUM)) {
					$r = $i . 'th';
					switch ($i) {
						case 1:
							$r = '1st';
							break;
						case 2:
							$r = '2nd';
							break;
						case 3:
							$r = '3rd';
							break;
					}

					echo ' <tr>
								<td width="10%" align="center">' . $r . '</td>
								<td width="30%" align="center"><a>' . $g[2] . '</a></td>
								<td width="15%" align="center">' . $g[5] . '</td>
								<td width="15%" align="center">' . $g[7] . '</td>
								<td width="15%" align="center">' . $g[8] . '</td>
							</tr>';
					$i++;
				}
				?>
				</tbody>
			</table>

		</section>
    </div>
</div>