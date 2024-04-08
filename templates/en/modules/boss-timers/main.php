<section class="sidebox_topvoters topvoter sidebox">
    <h4 class="sidebox_title border_box">
        <i>BOSS RECORDS</i>
        <div class="topvoter_desc"><a href="/?p=boss-records">VIEW COMPLETE RECORDS</a></div>
    </h4>
    <div class="sidebox_body border_box">
        <?php
        // Array associativo con gli ID dei boss e i relativi tempi di respawn in secondi
        $bosses = [
            '2480' => 43200,
            '2481' => 43200,
            '835' => 43200,
            '1259' => 43200,
            '2469' => 54000,
            '2490' => 25200,
            '2483' => 43200,
            '2488' => 43200,
            '2485' => 25200,
            '2491' => 43200
        ];

        $currentTime = date("Y-m-d H:i:s");
        foreach ($bosses as $bossID => $respawnTime) {
            $mapID = getMapID($bossID); // Ottieni l'ID della mappa associata al boss

            $bossName = getBossName($conn, $bossID); // Ottieni il nome del boss

            $deathLog = getLatestDeathLog($conn, $bossID, $mapID); // Ottieni l'ultimo registro di morte del boss

            if ($deathLog) {
                $bossName = $deathLog['MobName'];
                $nextSpawnTime = getNextSpawnTime($deathLog['ActionTime'], $respawnTime);
                $showTime = $nextSpawnTime <= $currentTime ? "<i style='color:green'>NOW!</i>" : "<span id='boss-timer-$bossID' style='color:orange'>" . gmdate("H:i:s", strtotime($nextSpawnTime) - strtotime($currentTime)) . "</span>";
            } else {
                $showTime = 'Unknown';
            }

            echo "<div class='topvoter_row'>
                <div class='topvoter_col col_name'>
                    <a>$bossName</a>
                </div>
                <div class='topvoter_col col_vote'>
                     $showTime
                </div>
            </div>";
        }

        // Funzione per ottenere l'ID della mappa associata al boss
        function getMapID($bossID) {
            // Implementazione per ottenere l'ID della mappa
            switch ($bossID) {
                case '2480': return 18;
                case '2481': return 30;
                case '835': return 11;
                case '1259': return 58;
                case '2469': return 67;
                case '2490': return 45;
                case '2483': return 103;
                case '2488': return 46;
                case '2485': return 82;
                case '2491': return 87;
                default: return null;
            }
        }

        // Funzione per ottenere il nome del boss
        function getBossName($conn, $bossID) {
            $query = $conn->prepare("SELECT MobName FROM PS_GameDefs.dbo.Mobs WHERE MobID = :bossID");
            $query->execute(['bossID' => $bossID]);
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['MobName'] : 'Unknown';
        }

        // Funzione per ottenere l'ultimo registro di morte del boss
        function getLatestDeathLog($conn, $bossID, $mapID) {
            $query = $conn->prepare("SELECT TOP 1 MobName, CharName, ActionTime FROM PS_GameLog.dbo.Boss_Death_Log WHERE MobID = :bossID AND MapID = :mapID ORDER BY ActionTime DESC");
            $query->execute(['bossID' => $bossID, 'mapID' => $mapID]);
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        // Funzione per calcolare il prossimo tempo di spawn del boss
        function getNextSpawnTime($lastDeathTime, $respawnTime) {
            $nextSpawnTimestamp = strtotime($lastDeathTime) + $respawnTime;
            return date("Y-m-d H:i:s", $nextSpawnTimestamp);
        }
        ?>
    </div>
</section>
