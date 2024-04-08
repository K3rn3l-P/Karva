<?php
$bosses = array(
	
	  '2480' => '18' 
	, '2481' => '30'
	, '835' => '11'
	, '1259' => '58'
	, '2472' => '67'
	, '2483' => '103'
	, '2490' => '45'
	, '2488' => '46'
	, '2491' => '87'
	
	
	, '871' => '14'
	, '872' => '14'
	, '1977' => '19'
	, '1700' => '48'
	, '1701' => '48'
	, '1702' => '48'
	, '1703' => '48'
	, '1704' => '48'
	, '1705' => '48'
	, '1706' => '48'
	, '1716' => '48'
	, '2486' => '43'
	
	, '901' => '17'
	, '902' => '17'
	, '1978' => '20'
	, '1800' => '49'
	, '1801' => '49'
	, '1802' => '49'
	, '1803' => '49'
	, '1804' => '49'
	, '1805' => '49'
	, '1806' => '49'
	, '1816' => '49'
	, '2485' => '44'
);
?>

<div id="page-login" class="page page-login">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> Boss records</span>
	</div>
    <div class="page-body border_box self_clear">
		<div class="content">
			<div class="content_area">
				<p class="text-center"><i>Respawn time is random from -1 hour (early respawn) to +1 hour (late respawn) from the timer.</i></p><br>
				<table cellspacing=1 cellpadding=1 border=1 style='width:700px;'>
					<tr>
						<th style='color:#B67808'>PvP Boss Name</th>
						<th>Killed By</th>
						<th>Death Time</th>
						<th>Map</th>
						<th>Respawn Time</th>
					</tr>
					<?php
// Dichiarazione di una funzione per ottenere il nome della mappa
function getMapName($mapid, $conn) {
    $query = $conn->prepare("SELECT MapName FROM PS_GameDefs.dbo.Maps WHERE MapID = ?");
    $query->bindValue(1, $mapid, PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['MapName'] : '';
}

foreach ($bosses as $bossid => $mapid) {
    // Query per ottenere i dettagli della morte del boss
    $query1 = $conn->prepare("SELECT CharName, ActionTime FROM PS_GameLog.dbo.Boss_Death_Log WHERE MobID=? AND MapID=? ORDER BY ActionTime DESC LIMIT 1");
    $query1->bindValue(1, $bossid, PDO::PARAM_INT);
    $query1->bindValue(2, $mapid, PDO::PARAM_INT);
    $query1->execute();
    $row1 = $query1->fetch(PDO::FETCH_ASSOC);

    // Query per ottenere i dettagli del boss
    $query2 = $conn->prepare("SELECT MobName, RespawnTime FROM PS_GameDefs.dbo.Mobs WHERE MobID = ?");
    $query2->bindValue(1, $bossid, PDO::PARAM_INT);
    $query2->execute();
    $row2 = $query2->fetch(PDO::FETCH_ASSOC);
    
    // Determinazione del nome del boss
    $bossName = $row2 ? $row2['MobName'] : '';
    
    // Determinazione del tempo di respawn del boss
    $spawnTime = '';
    if ($row2 && isset($row2['RespawnTime'])) {
        $spawnTime = ($mapid == 48 || $mapid == 49 || $bossid == 872 || $bossid == 902 || $bossid == 1977 || $bossid == 1978) ? $row2['RespawnTime'] . ' Days' : $row2['RespawnTime'] . ' Hours';
    }
    
    // Determinazione del killer e del tempo di morte del boss
    $killerName = $row1 ? $row1['CharName'] : '';
    $deathTime = $row1 ? date("Y-m-d H:i:s", strtotime($row1['ActionTime'])) : '';
    
    // Ottenimento del nome della mappa
    $mapName = getMapName($mapid, $conn);

    // Stampa delle intestazioni della tabella se si tratta del primo boss della luce o dell'oscurità
    if ($bossid == 871) {
        echo "<tr><th style='color:#0066FF'>Light Boss Name</th><th>Killed By</th><th>Death Time</th><th>Map</th><th>Respawn Time</th></tr>";
    } elseif ($bossid == 901) {
        echo "<tr><th style='color:#FF0000'>Dark Boss Name</th><th>Killed By</th><th>Death Time</th><th>Map</th><th>Respawn Time</th></tr>";
    }

    // Stampa dei dettagli del boss nella tabella
    echo "<tr><td>$bossName</td><td>$killerName</td><td>$deathTime</td><td>$mapName</td><td>$spawnTime</td></tr>";
}
?>

				</table>
			</div>
		</div>
    </div>
</div>

