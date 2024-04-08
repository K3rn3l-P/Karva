<section class="sidebox_topvoters topvoter sidebox">
    <h4 class="sidebox_title border_box">
        <i>Weekly STARS Collectors</i>
        <div class="topvoter_desc"><a href="/?p=stars">VIEW COMPLETE LIST</a></div>
    </h4>
    <div class="sidebox_body border_box">
        <?php
        try {
            // Connessione al database
            include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php"); // Includi il file config.php per la connessione al database

            // Query per ottenere i primi 3 giocatori con più stelle
            $query = "SELECT TOP 3 C.CharName, IL.Stars 
                      FROM PS_GameData.dbo.Chars IL
                      LEFT JOIN PS_GameData.dbo.Chars C ON IL.CharID=C.CharID
                      ORDER BY IL.Stars DESC";

            // Esecuzione della query utilizzando uno statement preparato
            $statement = $conn->prepare($query);
            $statement->execute();

            // Recupero dei risultati
            $index = 1;
            while ($item = $statement->fetch(PDO::FETCH_ASSOC)) {
                include("row-item.php");
                $index++;
            }

            // Chiudere la connessione al database
            $conn = null;
        } catch (PDOException $e) {
            // Gestione degli errori
            echo "Errore durante l'accesso al database: " . $e->getMessage();
        }
        ?>
        <center>
            <div class="topvoter_info">Stars Rewards are delivered every week to Top 100 Players. For more informations check <a href='/?p=stars-reward'>here!</a></div>
        </center>
    </div>
</section>
