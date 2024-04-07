<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// Not logged
if (!$UserUID) {
    header("Location:$BackUrl");
    return;
}

date_default_timezone_set('Europe/Bucharest');
$vote = $_GET['vote'];
$url = "";

switch ($vote) {
    case "xtreme":
        $url = 'https://www.xtremetop100.com/in.php?site=1132372571';
        break;
    case "gaming":
        $url = 'https://www.gamingtop100.net/in-18653';
        break;
    case "oxigen":
        $url = '#';
        break;
    case "arena":
        $url = 'https://www.arena-top100.com/index.php?a=in&u=anonymous';
        break;
    case "topg":
        $url = '#';
        break;
	default:
		echo "<script>window.close();</script>";	
		return;
}

$time = date("Y-m-d H:i:s.000");

$queryTime = $conn->prepare('SELECT TOP 1 Date FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = ? AND Confirm = 1 ORDER BY Row DESC');
$queryTime->bindValue(1, $UserUID, PDO::PARAM_INT);
$queryTime->bindValue(2, $userip, PDO::PARAM_INT);
$queryTime->bindValue(3, $vote, PDO::PARAM_INT);
$queryTime->execute();
$rowTime = $queryTime->fetch(PDO::FETCH_NUM);

if (($time > $rowTime[0]) || ($rowTime[0] == NULL)) {
	$date = date('Y-m-d H:i:s', strtotime('now +12 hours'));
	// New time
	$query1 = $conn->prepare("UPDATE PS_Website.dbo.Vote SET Confirm = 1 WHERE UserUID = ? AND VotePage = ? ");
	$query1->bindValue(1, $UserUID, PDO::PARAM_INT);
	$query1->bindValue(2, $vote, PDO::PARAM_INT);
	$query1->execute();

	$queryNewTime = $conn->prepare("INSERT INTO PS_Website.dbo.Vote VALUES (?,?,?,0,?)");
	$queryNewTime->bindValue(1, $UserUID, PDO::PARAM_INT);
	$queryNewTime->bindValue(2, $userip, PDO::PARAM_INT);
	$queryNewTime->bindValue(3, $vote, PDO::PARAM_INT);
	$queryNewTime->bindValue(4, $date, PDO::PARAM_INT);
	$queryNewTime->execute();

	header('location: ' . $url);
} else {
	echo "<script>window.close();</script>";
}