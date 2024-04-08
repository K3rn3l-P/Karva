<section class="sidebox_topvoters topvoter sidebox">
	<h4 class="sidebox_title border_box">
		<i>WEEKLY PvP Ranking</i>
		<div class="topvoter_desc"><a href="/?p=ranks&type=1">VIEW COMPLETE RANKING</a></div>
	</h4>
    <div class="sidebox_body border_box">
		<?php
		// Connessione al database
		include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

		try {
			// Query per ottenere i primi 3 giocatori con più uccisioni nella settimana corrente
			$query = "SELECT TOP 3 C.CharName, ISNULL(WK,0) AS WK
					 FROM (SELECT TOP 3 COUNT(1) AS [WK], CharID FROM PS_GameLog.dbo.Kills WHERE DT > DATEADD(WEEK,-1,CURRENT_TIMESTAMP) GROUP BY CharID ORDER BY WK DESC) W
					 LEFT JOIN PS_GameData.dbo.Chars C ON W.CharID = C.CharID
					 WHERE C.Del = 0
					 ORDER BY WK DESC";

			// Esecuzione della query utilizzando uno statement preparato
			$statement = $conn->prepare($query);
			$statement->execute();

			// Recupero dei risultati
			$index = 1;
			while ($item = $statement->fetch(PDO::FETCH_ASSOC)) {
				include("row-item.php");
				$index++;
			}
		} catch (PDOException $e) {
			// Gestione degli errori
			echo "Errore durante l'accesso al database: " . $e->getMessage();
		}
		?>
		<center>
			<div class="topvoter_info">Fight your opponents in PvP battles and claim PvP Rewards. For more information check <a href='/?p=pvp-reward'>here!</a></div>
		</center>
     </div>
</section>
