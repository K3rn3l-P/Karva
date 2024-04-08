<?php
// Include il file di configurazione solo se necessario
if (!defined('INCLUDED')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
    define('INCLUDED', true);
}

// Verifica se l'ID dell'articolo è stato inviato tramite POST e lo sanifica
$itemID = isset($_POST['i']) ? intval($_POST['i']) : 0;

// Controllo se l'ID dell'articolo è valido
if ($itemID <= 0) {
    echo '<h1 style="text-align: center; margin: 50px;">Invalid item selection</h1>';
    return;
}

try {
// Query per ottenere le mappe associate all'articolo
$search = $conn->prepare("SELECT mn.MapName, mn.MapID
        FROM PS_GameDefs.dbo.MobItems mi 
        INNER JOIN PS_GameDefs.dbo.Mobs m ON m.MobID = mi.MobID 
        INNER JOIN PS_GameDefs.dbo.MapNames mn ON m.MapID = mn.MapID
        INNER JOIN PS_GameDefs.dbo.Items i ON mi.Grade = i.Grade
        WHERE mi.MobID != 0 AND i.ItemID=:itemID 
        GROUP BY mn.MapName, mn.MapID ORDER BY mn.MapID ASC");
$search->bindParam(':itemID', $itemID, PDO::PARAM_INT);
$search->execute();


    // Output della tabella di risultati
    echo '<table class="drops">
        <tr>
            <th style="width:200px;">MapName</th>
            <th style="width: 544px;">Mobs</th>
        </tr>';

		$rowID = 1;
		while ($s = $search->fetch(PDO::FETCH_ASSOC)) {
			echo '<tr>
				<td style="text-align:center;">' . htmlspecialchars($s['MapName']) . '</td>'; // Qui viene applicata la sanitizzazione
		
			// Query per ottenere i mobs associati all'articolo e alla mappa corrente
			$mobs = $conn->prepare("SELECT m.MobName, m.Level, mi.ItemOrder, mi.DropRate
						FROM PS_GameDefs.dbo.MobItems mi 
						INNER JOIN PS_GameDefs.dbo.Mobs m ON m.MobID = mi.MobID 
						INNER JOIN PS_GameDefs.dbo.MapNames mn ON m.MapID = mn.MapID
						INNER JOIN PS_GameDefs.dbo.Items i ON mi.Grade = i.Grade
						WHERE mi.MobID != 0 AND mi.DropRate > 0 AND i.ItemID=:itemID AND m.MapID = :mapID
						ORDER BY mi.DropRate DESC");
			$mobs->bindParam(':itemID', $itemID, PDO::PARAM_INT);
			$mobs->bindParam(':mapID', $s['MapID'], PDO::PARAM_INT);
			$mobs->execute();
		
			echo '<td><table class="mobs">';
			if ($rowID == 0) {
				echo '<tr>
						<th style="width:300px;">Name</th>
						<th style="width:122px;">Level</th>
						<th style="width:122px;">Drop Rate</th>
					</tr>';
			}
			$rowID = 1;
			while ($mob = $mobs->fetch(PDO::FETCH_ASSOC)) {
				$droprate = $mob['DropRate'];
				if ($mob['ItemOrder'] > 6) {
					$droprate = $droprate / 10000;
				}
				echo '<tr>
						<td style="width:300px;text-align:left;">' . htmlspecialchars($mob['MobName']) . '</td>
						<td style="width:122px;">' . $mob['Level'] . '</td>
						<td style="width:122px;">' . $droprate . '%</td>
					</tr>';
			}
			echo '</table></td></tr>';
		}
    echo '</table>';
} catch (PDOException $e) {
    // Gestione degli errori del database
    echo '<h1 style="text-align: center; margin: 50px;">An error occurred while fetching data</h1>';
    error_log('Database Error: ' . $e->getMessage());
}
?>
