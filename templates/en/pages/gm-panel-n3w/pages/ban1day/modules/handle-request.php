<?php
$command = $conn->prepare($query);
$command->bindParam(1, $val, PDO::PARAM_INT);
$command->execute();
$result = $command->fetch(PDO::FETCH_NUM);
if ($result[0] == NULL) {
	echo "User not found";
	return;
}

$date = date("Y-m-d G:i");

switch ($days_ban) {
	case 1:
		$ban_day = 1;
		$ban_year = 0;
		$ban_text = "1 Day";
		break;
	
}

$year = date("Y");
$month = date("m");
$day = date("d");
$hour = date("G");
$min = date("i");

$bann_begin = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $min;

$test_day = $day + $ban_day;
$test_year = $year + $ban_year;
$last_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

if ($test_day > $last_day) {
	$bann_day = $test_day - $last_day;
	$bann_month = $month + 1;
} else {
	$bann_day = $test_day;
	$bann_month = $month;
}
if ($bann_month == 13) {
	$bann_year = $test_year + 1;
	$bann_month = 1;
} else {
	$bann_year = $test_year;
}

$bann_end = $bann_year . "-" . $bann_month . "-" . $bann_day . " " . $hour . ":" . $min;


$querybannDate = $conn->prepare("INSERT INTO PS_UserData.dbo.Users_Bann (UserUID, DaysBann, BanDate, Sbandate, Reason, GM_ID) VALUES ('" . $result[0] . "','" . $ban_text . "','" . $date . "','" . $bann_end . "','" . $reason . "','" . $UserID . "')");
$querybannDate->execute();

$queryBan = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status = '-5', Leave = 0 WHERE UserUID = ?");
$queryBan->bindParam(1, $result[0], PDO::PARAM_INT);
$queryBan->execute();

$queryKick = $conn->prepare("EXEC [PS_GameDefs].[dbo].[Command] @serviceName = N'ps_game', @cmmd = N'/kickuid $result[0]'");
$queryKick->bindParam(1, $result[0], PDO::PARAM_INT);
$queryKick->execute();

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Block user', 'UID: $result[0]; USERNAME: $result[1]', '$UserIP')");
$query->execute();

echo "$result[1] banned";
