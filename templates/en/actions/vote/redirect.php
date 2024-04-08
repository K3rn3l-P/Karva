<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autenticato
if (!$UserUID) {
    header("Location: $BackUrl");
    return;
}

date_default_timezone_set('Europe/Bucharest');

// Ottieni il voto e imposta l'URL corrispondente
$vote = isset($_GET['vote']) ? $_GET['vote'] : '';
$url = '';

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

$time = date("Y-m-d H:i:s");

// Controlla se l'utente ha già votato entro le ultime 12 ore
$queryTime = $conn->prepare('SELECT TOP 1 Date FROM PS_Website.dbo.Vote WHERE (UserUID = ? OR UserIP = ?) AND VotePage = ? AND Confirm = 1 ORDER BY Row DESC');
$queryTime->bindValue(1, $UserUID, PDO::PARAM_INT);
$queryTime->bindValue(2, $userip, PDO::PARAM_STR);
$queryTime->bindValue(3, $vote, PDO::PARAM_STR);
$queryTime->execute();
$rowTime = $queryTime->fetchColumn();

if ($time > $rowTime || empty($rowTime)) {
    $newTime = date('Y-m-d H:i:s', strtotime('+12 hours'));

    // Aggiorna il voto confermato
    $queryUpdate = $conn->prepare("UPDATE PS_Website.dbo.Vote SET Confirm = 1 WHERE UserUID = ? AND VotePage = ?");
    $queryUpdate->bindValue(1, $UserUID, PDO::PARAM_INT);
    $queryUpdate->bindValue(2, $vote, PDO::PARAM_STR);
    $queryUpdate->execute();

    // Inserisci un nuovo voto
    $queryNewVote = $conn->prepare("INSERT INTO PS_Website.dbo.Vote (UserUID, UserIP, VotePage, Confirm, Date) VALUES (?, ?, ?, 0, ?)");
    $queryNewVote->bindValue(1, $UserUID, PDO::PARAM_INT);
    $queryNewVote->bindValue(2, $userip, PDO::PARAM_STR);
    $queryNewVote->bindValue(3, $vote, PDO::PARAM_STR);
    $queryNewVote->bindValue(4, $newTime, PDO::PARAM_STR);
    $queryNewVote->execute();

    // Reindirizza all'URL specificato
    header('Location: ' . $url);
} else {
    // Chiudi la finestra se l'utente ha già votato nelle ultime 12 ore
    echo "<script>window.close();</script>";
}
?>
