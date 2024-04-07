<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// Not logged
if (!$UserUID)
    return;

if (isset($_SERVER['HTTP_REFERER'])) {
    $vote_page = $_SERVER['HTTP_REFERER'];
} else {
    $vote_page = '';
}

$vote = '';
switch ($vote_page) {
	case 'https://www.xtremetop100.com/':
    case 'https://www.xtremetop100.com/shaiya':
    case 'https://www.xtremetop100.com/out.php?site=1132372571':
        $vote = 'xtreme';
        break;
    case 'http://www.gamingtop100.net/':
    case 'http://www.gamingtop100.net/shaiya/':
	case 'https://www.gamingtop100.net/':
    case 'https://www.gamingtop100.net/shaiya/':
    case 'http://www.gamingtop100.net/server/18653/shaiya-elixir':
    case 'http://www.gamingtop100.net/out.php?id=18653':
	case 'https://www.gamingtop100.net/out.php?id=18653':
	case 'https://www.gamingtop100.net/server/18653/shaiya-elixir':
	case 'http://gamingtop100.net/out.php?id=18653':
	case 'http://gamingtop100.net/server/18653/shaiya-elixir':
        $vote = 'gaming';
        break;
    case 'http://www.oxigen-top100.com/':
    case 'http://www.oxigen-top100.com/shaiya/':
    case 'http://www.oxigen-top100.com/details-456633.html':
    case 'http://www.oxigen-top100.com/out-456633.html':
        $vote = 'oxigen';
        break;
    case 'https://www.arena-top100.com/Shaiya/':
	case 'https://www.arena-top100.com/shaiya-private-servers/':
	case 'https://www.arena-top100.com/details/anonymous/':
	case 'https://www.arena-top100.com/':
        $vote = 'arena';
        break;
    case 'https://topg.org/shaiya-private-servers/':
    case 'https://topg.org/server-shaiya-lands-id510449':
    case 'https://topg.org/visit-510449':
        $vote = 'topofgames';
        break;
	default:
		return;
}

$query = $conn->prepare('SELECT TOP 1 Confirm FROM PS_Website.dbo.Vote WHERE UserUID = ? AND VotePage = ? ORDER BY Row DESC');
$query->bindValue(1, $UserUID, PDO::PARAM_INT);
$query->bindValue(2, $vote, PDO::PARAM_INT);
$query->execute();
$row = $query->fetch(PDO::FETCH_NUM);

if ($row[0] != NULL && $row[0] == 0) {
    $query1 = $conn->prepare("UPDATE PS_Website.dbo.Vote SET Confirm = 1 WHERE UserUID = ? AND VotePage = ?");
    $query1->bindValue(1, $UserUID, PDO::PARAM_INT);
    $query1->bindValue(2, $vote, PDO::PARAM_INT);
    $query1->execute();

    $query2 = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point += 10, VotePoint += 1 WHERE UserUID = ?");
    $query2->bindValue(1, $UserUID, PDO::PARAM_INT);
    $query2->execute();

    header("location:/?p=vote");
    exit();
}
