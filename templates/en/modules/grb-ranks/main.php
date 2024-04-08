<?php
// Connessione al database
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Query per selezionare le prime 50 gilde con Country = 0 ordinate per punti in modo decrescente
$query = "SELECT TOP 50 * FROM PS_GameData.dbo.Guilds WHERE Del = 0 AND Country = 0 ORDER BY GuildPoint DESC";
$qGuild = $conn->prepare($query);
$qGuild->execute();

// Variabile per contare il numero di gilde
$i = 1;

// Ciclo per iterare sui risultati della query e visualizzare le informazioni delle gilde
while ($g = $qGuild->fetch(PDO::FETCH_NUM)) {
    // Determina l'ordine della gilda (1st, 2nd, 3rd, ecc.)
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
        default:
            $r = $i . 'th';
    }

    // Visualizza le informazioni della gilda
    echo "<div>$r Guild: $g[0], Guild Point: $g[1]</div>";

    // Incrementa il contatore delle gilde
    $i++;
}
?>
