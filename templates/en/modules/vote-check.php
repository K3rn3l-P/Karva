<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente non è loggato
if (!$UserUID || !isset($_SERVER['HTTP_REFERER'])) {
    return;
}

$vote_page = $_SERVER['HTTP_REFERER'];
$vote = '';

// Mappa le pagine di voto ai relativi siti
$vote_mappings = [
    'xtreme' => [
        'https://www.xtremetop100.com/',
        'https://www.xtremetop100.com/shaiya',
        'https://www.xtremetop100.com/out.php?site=1132372571'
    ],
    'gaming' => [
        'http://www.gamingtop100.net/',
        'http://www.gamingtop100.net/shaiya/',
        'https://www.gamingtop100.net/',
        'https://www.gamingtop100.net/shaiya/',
        'http://www.gamingtop100.net/server/18653/shaiya-Duff',
        'http://www.gamingtop100.net/out.php?id=18653',
        'https://www.gamingtop100.net/out.php?id=18653',
        'https://www.gamingtop100.net/server/18653/shaiya-Duff',
        'http://gamingtop100.net/out.php?id=18653',
        'http://gamingtop100.net/server/18653/shaiya-Duff'
    ],
    'oxigen' => [
        'http://www.oxigen-top100.com/',
        'http://www.oxigen-top100.com/shaiya/',
        'http://www.oxigen-top100.com/details-456633.html',
        'http://www.oxigen-top100.com/out-456633.html'
    ],
    'arena' => [
        'https://www.arena-top100.com/Shaiya/',
        'https://www.arena-top100.com/shaiya-private-servers/',
        'https://www.arena-top100.com/details/anonymous/',
        'https://www.arena-top100.com/'
    ],
    'topofgames' => [
        'https://topg.org/shaiya-private-servers/',
        'https://topg.org/server-shaiya-lands-id510449',
        'https://topg.org/visit-510449'
    ]
];

// Trova il voto corrispondente alla pagina di provenienza
foreach ($vote_mappings as $key => $pages) {
    if (in_array($vote_page, $pages)) {
        $vote = $key;
        break;
    }
}

// Se il voto è stato trovato
if (!empty($vote)) {
    $query = $conn->prepare('SELECT TOP 1 Confirm FROM PS_Website.dbo.Vote WHERE UserUID = ? AND VotePage = ? ORDER BY Row DESC');
    $query->execute([$UserUID, $vote]);
    $row = $query->fetch(PDO::FETCH_NUM);

    // Se l'utente non ha ancora confermato il voto
    if (!empty($row) && $row[0] != NULL && $row[0] == 0) {
        $update_query = $conn->prepare("UPDATE PS_Website.dbo.Vote SET Confirm = 1 WHERE UserUID = ? AND VotePage = ?");
        $update_query->execute([$UserUID, $vote]);

        $increment_points_query = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point += 10, VotePoint += 1 WHERE UserUID = ?");
        $increment_points_query->execute([$UserUID]);

        header("Location: /?p=vote");
        exit();
    }
}
