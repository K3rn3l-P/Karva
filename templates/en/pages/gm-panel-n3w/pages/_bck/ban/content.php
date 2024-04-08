<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= htmlspecialchars($subpages[$subpage]["Title"]) ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<?php
		$query = "";
		$val = "";
		if (isset($_POST["UserID"]) && !empty($_POST["UserID"])) {
			$val = $_POST["UserID"];
			$query = "SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserID=?";
		} elseif (isset($_POST["CharName"]) && !empty($_POST["CharName"])) {
			$val = $_POST["CharName"];
			$query = "SELECT UserUID, UserID FROM PS_UserData.dbo.Users_Master WHERE UserUID=(SELECT TOP 1 UserUID FROM PS_GameData.dbo.Chars WHERE CharName=? ORDER BY Del)";
		}
		$days_ban = isset($_POST["days"]) && is_numeric($_POST["days"]) ? $_POST["days"] : 0;
		$reason = isset($_POST["reason"]) ? $_POST['reason'] : "";
		
		if ($query && $days_ban) 
			include("modules/handle-request.php");
		?>

		<div id="spiega">
			<form method="POST">
				<table>
					<tr>
						<td>Account Name:</td>
						<td><input type="text" name="UserID"/></td>
					</tr>
					<tr>
						<td>Character Name:</td>
						<td><input type="text" name="CharName"/></td>
					</tr>
					<tr>
						<td>Ban Type:</td>
						<td><select name="days">
								<option value="1">1 Day ban</option>
								<option value="2">2 Days ban</option>
								<option value="3">3 Days ban</option>
								<option value="4">7 Days ban</option>
								<option value="5">15 Days ban</option>
								<option value="6">30 Days ban</option>
								<option value="7">Permanent</option>
							</select></td>
					</tr>
					<tr>
						<td>Reason:</td>
						<td><input type="text" maxlength="160" placeholder="e.g: Stat Padding" name="reason" id="reason"/></td>
					</tr>
				</table>
				<p><input type="submit" value="Submit" name="submit" style="margin: 10px 0 0 320px;"/></p>
			</form>
		</div>

		<?php
		$date = date("Y-m-d G:i", time());
		$queryAccount2 = $conn->prepare('SELECT * FROM PS_UserData.dbo.Users_Bann ORDER BY BanDate DESC');
		$queryAccount2->execute();
		//table banned   
		echo "<table id='control'>";
		echo "</br>";
		echo "<tr><th>Account</th><th>Period</th><th>Ban Date</th><th>Sban Date</th><th>Reason</th></tr>";
		while ($charAccount2 = $queryAccount2->fetch(PDO::FETCH_NUM)) {
			$queryAccount1 = $conn->prepare('SELECT UserID FROM PS_UserData.dbo.Users_Master WHERE UserUID= ?');
			$queryAccount1->bindParam(1, $charAccount2[1], PDO::PARAM_INT);
			$queryAccount1->execute();
			$charAccount1 = $queryAccount1->fetch(PDO::FETCH_NUM);
			echo "<tr><td>" . htmlspecialchars($charAccount1[0]) . "</td><td>" . htmlspecialchars($charAccount2[2]) . "</td><td>" . htmlspecialchars($charAccount2[3]) . "</td><td>" . htmlspecialchars($charAccount2[4]) . "</td><td>" . htmlspecialchars($charAccount2[5]) . "</td></tr>";
		}
		echo "</table>";
		?>
		<!-- end content -->	

    </div>
</div>
