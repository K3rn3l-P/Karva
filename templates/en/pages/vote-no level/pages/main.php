<div class="mainbox-header page-header border_box">
    <div style="margin:15px 0 0 30px;float:right;">
        <a href="/?p=billing"; class="nice_button" style="margin-right:10px;" >Buy SP</a>
		<a class="nice_button" style="margin-right:10px;" ><?= $currencyName, ": ", number_format($Point, 0, '.', ' '), " ", $currencyCode ?> </a>
    </div>
    <div style="clear:both;"></div>
</div>
<div class="mainbox-body page-body self_clear">
    
	
	
	
	
	
	
	
 <div id="vote">
		<?php
		date_default_timezone_set('Europe/Bucharest');
		$time = date("Y-m-d H:i:s.000");


		$query0 = $conn->prepare("SELECT TOP 1 Date, Confirm FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = 'xtreme' ORDER BY Row DESC");
		$query0->bindValue(1, $UserUID, PDO::PARAM_INT);
		$query0->bindValue(2, $userip, PDO::PARAM_INT);
		$query0->execute();
		$row0 = $query0->fetch(PDO::FETCH_NUM);

		if (($row0[1] == 0) && ($row0[1] != NULL) && ($row0[0] > $time)) {

			$xtreme = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=xtreme' target='_blank' class='votetime'>Complete the vote</a>";

		} elseif (($row0[0] == NULL) || ($row0[0] < $time)) {

			$xtreme = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=xtreme' target='_blank' class='btn-vote'>Vote Now</a>";

		} else {
			$newTime = strtotime($row0[0]);
			$time1 = strtotime($time);
			$countdown = $newTime - $time1;
			$endDate = gmdate("H:i:s", $countdown);
			$xtreme = "<span id='xtreme-timer' class='votetime'>$endDate</span>";
			echo "<script>startTimer($countdown, 'xtreme-timer', 'Reload page')</script>";
		}

		$query1 = $conn->prepare("SELECT TOP 1 Date, Confirm FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = 'gaming' ORDER BY Row DESC");
		$query1->bindValue(1, $UserUID, PDO::PARAM_INT);
		$query1->bindValue(2, $userip, PDO::PARAM_INT);
		$query1->execute();
		$row1 = $query1->fetch(PDO::FETCH_NUM);

		if (($row1[1] == 0) && ($row1[1] != NULL) && ($row1[0] > $time)) {

			$gaming = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=gaming' target='_blank' class='votetime'>Complete the vote</a>";

		} elseif (($row1[0] == NULL) || ($row1[0] < $time)) {

			$gaming = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=gaming' target='_blank' class='btn-vote'>Vote Now</a>";

		} else {
			$newTime = strtotime($row1[0]);
			$time2 = strtotime($time);
			$countdown = $newTime - $time2;
			$endDate = gmdate("H:i:s", $countdown);
			$gaming = "<span id='gaming-timer' class='votetime'>$endDate</span>";
			echo "<script>startTimer($countdown, 'gaming-timer', 'Reload page')</script>";
		}


		$query2 = $conn->prepare("SELECT TOP 1 Date, Confirm FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = 'oxigen' ORDER BY Row DESC");
		$query2->bindValue(1, $UserUID, PDO::PARAM_INT);
		$query2->bindValue(2, $userip, PDO::PARAM_INT);
		$query2->execute();
		$row1 = $query2->fetch(PDO::FETCH_NUM);

		if (($row1[1] == 0) && ($row1[1] != NULL) && ($row1[0] > $time)) {

			$oxigen = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=oxigen' target='_blank' class='votetime'>Complete the vote</a>";

		} elseif (($row1[0] == NULL) || ($row1[0] < $time)) {

			$oxigen = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=oxigen' target='_blank' class='btn-vote'>Vote Now</a>";

		} else {
			$newTime = strtotime($row1[0]);
			$time3 = strtotime($time);
			$countdown = $newTime - $time3;
			$endDate = gmdate("H:i:s", $countdown);
			$oxigen = "<span id='oxigen-timer' class='votetime'>$endDate</span>";
			echo "<script>startTimer($countdown, 'oxigen-timer', 'Reload page')</script>";
		}


		$query3 = $conn->prepare("SELECT TOP 1 Date, Confirm FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = 'arena' ORDER BY Row DESC");
		$query3->bindValue(1, $UserUID, PDO::PARAM_INT);
		$query3->bindValue(2, $userip, PDO::PARAM_INT);
		$query3->execute();
		$row1 = $query3->fetch(PDO::FETCH_NUM);

		if (($row1[1] == 0) && ($row1[1] != NULL) && ($row1[0] > $time)) {

			$arena = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=arena' target='_blank' class='votetime'>Complete the vote</a>";

		} elseif (($row1[0] == NULL) || ($row1[0] < $time)) {

			$arena = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=arena' target='_blank' class='btn-vote'>Vote Now</a>";

		} else {
			$newTime = strtotime($row1[0]);
			$time4 = strtotime($time);
			$countdown = $newTime - $time4;
			$endDate = gmdate("H:i:s", $countdown);
			$arena = "<span id='arena-timer' class='votetime'>$endDate</span>";
			echo "<script>startTimer($countdown, 'arena-timer', 'Reload page')</script>";
		}


		$query4 = $conn->prepare("SELECT TOP 1 Date, Confirm FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = 'topofgames' ORDER BY Row DESC");
		$query4->bindValue(1, $UserUID, PDO::PARAM_INT);
		$query4->bindValue(2, $userip, PDO::PARAM_INT);
		$query4->execute();
		$row1 = $query4->fetch(PDO::FETCH_NUM);

		if (($row1[1] == 0) && ($row1[1] != NULL) && ($row1[0] > $time)) {

			$topofgames = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=topofgames' target='_blank' class='votetime'>Complete the vote</a>";

		} elseif (($row1[0] == NULL) || ($row1[0] < $time)) {

			$topofgames = "<a href='$TemplateUrl/actions/vote/redirect.php?vote=topofgames' target='_blank' class='btn-vote'>Vote Now</a>";

		} else {
			$newTime = strtotime($row1[0]);
			$time5 = strtotime($time);
			$countdown = $newTime - $time5;
			$endDate = gmdate("H:i:s", $countdown);
			$topofgames = "<span id='topofgames-timer' class='votetime'>$endDate</span>";
			echo "<script>startTimer($countdown, 'topofgames-timer', 'Reload page')</script>";
		}
		?>



		<div class="vote-item"><img src="<?= $AssetUrl ?>images/vote/xtremetop100.jpg">
			<div id="xtremetop100"><?= $xtreme ?></div>
			<div align="center"><b>10</b>
				<div class="apIconShop"></div>				
			</div>
		</div>
		
		
		
		<div class="vote-item"><img src="<?= $AssetUrl ?>images/vote/arena.png">
			<div id="arena"><?= $arena ?></div>
			<div align="center"><b>10</b>
				<div class="apIconShop"></div>				
			</div>
		</div>
		
		
		
		<div class="vote-item"><img src="<?= $AssetUrl ?>images/vote/gaming.png">
			<div id="gaming"><?= $gaming ?></div>
			<div align="center"><b>10</b>
				<div class="apIconShop"></div>
				
			</div>
		</div>
		
		
		

	</div>
	

	<li>Each valid vote brings you 10 SP!</li>

	<li><span style="color:#ff0000">Multiple Vote Accounts is strictly forbidden and ilegale! Accounts will be banned PERMANENTLY & SP removed.</span></li>
    	
	

</div>