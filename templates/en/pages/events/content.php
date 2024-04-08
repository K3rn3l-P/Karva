<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> Weekly events</span>
	</div>
    <div class="page-body border_box self_clear">	
		<style>
			.day {
				text-transform: uppercase;
				border: 2px solid #0a070182;
				background: #30c6ce;
				padding: 5px;
				width: 728px;
				color: #000000;
				font-size: 20px;
			}

			.time {
				margin: 5px;
				font-size: 20px;
				border: 2px solid grey;
				background-color: #9e9e9e;
				color: #050505;
			}

			.no-event {
				font-size: 16px;
				color: #aaa;
				line-height: 2;
			}

			.is-event {
				color: #c9c4c4;
				font-size: 20px;
				padding: 10px;
			}

			.minutes {
				color: lightyellow;
			}
		</style>

		<?php
		if ($IsStaff) {
			include("modules/create-module.php");
		}
		?>

		<table>
		<?
		$dt = new DateTime();
		if (isset($_GET['year']) && isset($_GET['week'])) {
			$dt->setISODate($_GET['year'], $_GET['week']);
		} else {
			$dt->setISODate($dt->format('o'), $dt->format('W'));
		}
		$year = $dt->format('o');
		$week = $dt->format('W');


		do {
			$qEc = $conn->prepare("SELECT COUNT(*) FROM PS_WebSite.dbo.Events$lang WHERE datediff(day, DateBegin, ?) = 0");
			$dateStr = date("Y-m-d");
			$qEc->bindParam(1, $dateStr, PDO::PARAM_STR);
			$qEc->execute();
			$rEc = $qEc->fetch(PDO::FETCH_NUM);

			$weekDayName = "";
			switch ($dt->format('w')) {
				case 0:
					$weekDayName = 'Sunday';
					break;
				case 1:
					$weekDayName = 'Monday';
					break;
				case 2:
					$weekDayName = 'Tuesday';
					break;
				case 3:
					$weekDayName = 'Wednesday';
					break;
				case 4:
					$weekDayName = 'Thursday';
					break;	
				case 5:
					$weekDayName = 'Friday';
					break;	
				case 6:
					$weekDayName = 'Saturday';
					break;	
				
			}
			echo '<tr><td class="day">' . $weekDayName . '</td></tr>';
			if ($rEc) {
				$qE = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Events$lang WHERE datediff(day, DateBegin, ?) = 0 ORDER BY DateBegin ASC");
				$dateStr = $dt->format('Y-m-d');
				$qE->bindParam(1, $dateStr, PDO::PARAM_STR);
				$qE->execute();
				while ($result = $qE->fetch(PDO::FETCH_NUM)) {
					echo '<tr><td class="is-event"><span class="time">' . date_format(date_create($result[3]), 'H:i') . '</span>' . $result[1] . ' <span class="minutes" > </span>';

					if ($IsStaff) {
						echo " <a href='?p=$page&e=$result[0]'>EDIT</a> | <a href='$TemplateUrl/actions/events/delete-event.php?r=$result[0]'>DELETE</a>";
					}
					echo '<br>' . $result[2] . '</td></tr>';
				}
			} else {
				echo '<tr><td class="no-event">Staff will make events and announce in FB/game.</td></tr>';
			}
			$dt->modify('+1 day');
		} while ($week == $dt->format('W'));
		?>
		</table>

		<div class="news_pagi border_box self_clear">										
			<div class="news_pagi-right">
				<a class="nice_button" href="?p=<?= $page ?>&week=<?= ($week - 1) ?>&year=<?= $year ?>">← Pre Week</a>
				<a class="nice_button" href="?p=<?= $page ?>&week=<?= ($week + 1) ?>&year=<?= $year ?>">Next Week &rarr;</a>
			</div>
		</div>
		
	</div>
</div>