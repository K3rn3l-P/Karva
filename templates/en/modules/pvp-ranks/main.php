<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php"); // Include il file config.php per la connessione al database
?>


<section class="sidebox_topvoters topvoter sidebox">
	<h4 class="sidebox_title border_box">
		<i>PvP RANKING</i>
		<div class="topvoter_desc"><a href="/?p=ranks">VIEW COMPLETE RANKING</a></div>
	</h4>
    <div class="sidebox_body border_box">
		<?php
		try {
			// Connessione al database
			include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php"); // Includi il file config.php per la connessione al database

			// Query per ottenere i primi 5 giocatori con più K1
			$query = "SELECT TOP 5 C.CharName, C.K1 
						FROM PS_GameData.dbo.Chars C
						JOIN PS_UserData.dbo.Users_Master UM ON UM.UserUID = C.UserUID
						WHERE C.Del = 0 AND UM.Status = 0
						ORDER BY K1 DESC";

			// Esecuzione della query utilizzando uno statement preparato
			$statement = $conn->prepare($query);
			$statement->execute();

			// Recupero dei risultati in un array associativo
			$players = $statement->fetchAll(PDO::FETCH_ASSOC);

			// Output dei risultati
			$index = 1;
			foreach ($players as $player) {
				// Assicurati che i dati siano adeguatamente sanitizzati prima dell'output
				$charName = htmlspecialchars($player['CharName']);
				$k1 = htmlspecialchars($player['K1']);
				
				echo "<div>$index. Player: $charName, K1: $k1</div>";
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
			<div class="topvoter_info">Fight your opponents in PvP battles and claim PvP Rewards. For more information check <a href='/?p=pvp-reward'>here!</a></div>
		</center>
     </div>
</section>
