<div class='user-block'>
	<?php

	$queryName = $conn->prepare('SELECT * FROM PS_GameData.dbo.Chars WHERE UserUID= ? Order By Slot');
	$queryName->bindParam(1, $userDetails[0], PDO::PARAM_INT);
	$queryName->execute();
	while ($charDetails = $queryName->fetch(PDO::FETCH_NUM)) {
		//row details
		//grow
		if ($charDetails[9] == 2) {
			$grow5 = "Normal";
		}
		if ($charDetails[9] == 3) {
			$grow5 = "Ultimate";
		}
		//sex
		if ($charDetails[14] == 0) {
			$sex = "Male";
		}
		if ($charDetails[14] == 1) {
			$sex = "Female";
		}
		//family
		if ($charDetails[8] == 0) {
			$family = "Human";
		}
		if ($charDetails[8] == 1) {
			$family = "Elf";
		}
		if ($charDetails[8] == 2) {
			$family = "Vail";
		}
		if ($charDetails[8] == 3) {
			$family = "Death Eater";
		}
		//class
		//light
		if (($charDetails[8] == 0) && ($charDetails[13] == 0)) {
			$class = "Fighter";
		}
		if (($charDetails[8] == 0) && ($charDetails[13] == 1)) {
			$class = "Defender";
		}
		if (($charDetails[8] == 0) && ($charDetails[13] == 5)) {
			$class = "Priest";
		}
		if (($charDetails[8] == 1) && ($charDetails[13] == 2)) {
			$class = "Ranger";
		}
		if (($charDetails[8] == 1) && ($charDetails[13] == 3)) {
			$class = "Arcer";
		}
		if (($charDetails[8] == 1) && ($charDetails[13] == 4)) {
			$class = "Mage";
		}
		//dark
		if (($charDetails[8] == 3) && ($charDetails[13] == 0)) {
			$class = "Warrior";
		}
		if (($charDetails[8] == 3) && ($charDetails[13] == 1)) {
			$class = "Guardian";
		}
		if (($charDetails[8] == 2) && ($charDetails[13] == 5)) {
			$class = "Oracle";
		}
		if (($charDetails[8] == 2) && ($charDetails[13] == 2)) {
			$class = "Assassin";
		}
		if (($charDetails[8] == 3) && ($charDetails[13] == 3)) {
			$class = "Hunter";
		}
		if (($charDetails[8] == 2) && ($charDetails[13] == 4)) {
			$class = "Pagan";
		}
		//map
		$map5 = $charDetails[27];
		$map = getMapName($map5);

		echo "<table id='control'>";
		echo "<tr><th>Character Details: </th></tr>";
		echo "<tr><td>CharID: </td><td>" . $charDetails[3] . "</td></tr>";
		echo "<tr><td>Char Name: </td><td>" . $charDetails[4] . "</td></tr>";

		if ($charDetails[6] == 1) {
			$charStatus = "Deleted";
		} else {
			$charStatus = "Active";
		}
		echo "<tr><td>Status: </td><td>" . $charStatus . "</td></tr>";
		echo "<tr><td>Family: </td><td>" . $family . "</td></tr>";
		echo "<tr><td>Class: </td><td>" . $class . "</td></tr>";
		echo "<tr><td>Level: </td><td>" . $charDetails[15] . "</td></tr>";
		echo "<tr><td>Money: </td><td>" . $charDetails[30] . "</td></tr>";
		echo "<tr><td>Map: </td><td>" . $map . "</td></tr>";
		echo "<tr><td>Kills: </td><td>" . $charDetails[39] . "</td></tr>";
		echo "<tr><td>Deaths: </td><td>" . $charDetails[40] . "</td></tr>";
		if ($charDetails[50] == NULL) {
			$oldName = "None";
		} else {
			$oldName = $charDetails[50];
		}
		echo "<tr><td>Old Char Name: </td><td>" . $oldName . "</td></tr>";
		if ($charDetails[52] == 0) {
			$logStatus = "<span style='color:#FF0000'>Offline</span>";
		} else {
			$logStatus = "<span style='color:#00FF00'>Online</span>";
		}
		echo "<tr><td>Login Status: </td><td>" . $logStatus . "</td></tr>";
		echo "</table>";
	}

	//table account
	$UserStatus = $userDetails[7];

	echo "<table id='control'>";
	echo "<tr><th>Account Details</th></tr>";
	echo "<tr><td>UserUID: </td><td>" . $userDetails[0] . "</td></tr>";
	echo "<tr><td>Account Name: </td><td>" . $userDetails[1] . "</td></tr>";

	if ($UserInfo["AdminLevel"] >= 250 && $userDetails[5] < 255) {
		echo "<tr><td>Password: </td><td>" . $userDetails[2] . "</td></tr>";

	}
	echo "<tr><td>Email: </td><td>" . $userDetails[13] . "</td></tr>";

	if ($userDetails[7] == 1) {
		$userDetails1 = "<span style='color:#00FF00'>Online</span>";
	} else {
		$userDetails1 = "<span style='color:#FF0000'>Offline</span>";
	}
	echo "<tr><td>Login Status: </td><td>" . $userDetails1 . "</td></tr>";

	if ($userDetails[6] == -5) {
		$userDetails2 = "Yes";
	} else {
		$userDetails2 = "No";
	}
	echo "<tr><td>Banned ?!: </td><td>" . $userDetails2 . "</td></tr>";
	echo "<tr><td>Shaiya Points: </td><td>" . $userDetails[11] . "</td></tr>";
	echo "<tr><td>Vote Points: </td><td>" . $userDetails[14] . "</td></tr>";
	
	echo "<tr><td>Last Login Date: </td><td>" . $userDetails[ 3] . "</td></tr>";
	echo "<tr><td>IP Registration: </td><td>" . $userDetails[10] . "</td></tr>";
	echo "</table>";


//User Last Login IP
	$queryUserLogin = $conn->prepare('SELECT * FROM PS_GameLog.dbo.UserLoginStatus WHERE UserUID= ?');
	$queryUserLogin->bindParam(1, $userDetails[0], PDO::PARAM_INT);
	$queryUserLogin->execute();
	$charUserLogin = $queryUserLogin->fetch(PDO::FETCH_NUM);

	echo "<table id='control'>";
	echo "<tr><th>Account Last Log</th></tr>";
	echo "<tr><td>Login: </td><td>" . $charUserLogin[3] . "</td></tr>";
	echo "<tr><td>Logout: </td><td>" . $charUserLogin[4] . "</td></tr>";
	echo "<tr><td>Last Login IP: </td><td>" . $charUserLogin[8] . "</td></tr>";
	echo "</table>";
	?>
</div>
