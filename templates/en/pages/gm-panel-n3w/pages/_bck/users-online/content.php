<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

        <?php
        // Inserimento del log dell'azione
        $query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$UserUID, $UserID, 'Access to users-online page', '', $UserIP]);

        // Gestione della variabile di query $condition
        $faction = isset($_GET["faction"]) ? $_GET["faction"] : -1;
        switch ($faction) {
            case 0:
                $condition = 'AND u.Country = 0';
                break;
            case 1:
                $condition = 'AND u.Country = 1';
                break;
            default:
                $condition = '';
                break;
        }

        // Recupero del conteggio dei giocatori per fazione
        $factionCount = array(0 => 0, 1 => 0, 2 => 0);
        $query = "SELECT u.Country, COUNT(*) AS Cnt FROM PS_GameData.dbo.Chars c JOIN PS_GameData.dbo.UserMaxGrow u ON c.UserUID = u.UserUID WHERE c.LoginStatus = 1 GROUP BY u.Country";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $factionCount[$row['Country']] = $row['Cnt'];
        }
        $total = $factionCount[0] + $factionCount[1];

        // Recupero del conteggio dei giocatori per razza
        $familyCount = array(0 => 0, 1 => 0, 2 => 0, 3 => 0);
        $query = "SELECT Family FROM PS_GameData.dbo.Chars WHERE LoginStatus = 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $familyCount[$row['Family']]++;
        }
        ?>

        <div class="right">
            <a class="nice_button" style="margin-right: 10px;">Total players: <?= $total ?></a>
            <a class="nice_button" href="/?p=gm-panel-n3w&sp=users-online">All players</a>
            <a class="nice_button" href="/?p=gm-panel-n3w&sp=users-online&faction=0">Only light</a>
            <a class="nice_button" href="/?p=gm-panel-n3w&sp=users-online&faction=1">Only fury</a>
        </div>

        <div class="container" style="margin: 60px auto 0; width: 220px;">
            <!-- Faction -->
            <div class="faction-info">
                <div class="half left">
                    <div class="faction-item" data-tip="Alliance of Light">
                        <span class="icon light"></span>
                        <p id="light-count" class="orange"><?= $factionCount[0] ?></p>
                    </div>
                </div>
                <div class="half right">
                    <div class="faction-item" data-tip="Union of Fury">
                        <span class="icon dark"></span>
                        <p id="dark-count" class="orange"><?= $factionCount[1] ?></p>
                    </div>
                </div>
            </div>

            <!-- Race START -->
            <div class="race-info">
                <div class="row">
                    <div class="half left">
                        <div class="race-item" data-tip="Humans">
                            <span class="icon human"></span>
                            <p id="human-count"><?= $familyCount[0] ?></p>
                        </div>
                    </div>
                    <div class="half right">
                        <div class="race-item" data-tip="Nordeins">
                            <span class="icon da"></span>
                            <p id="da-count"><?= $familyCount[3] ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="half left">
                        <div class="race-item" data-tip="Elfs">
                            <span class="icon elf"></span>
                            <p id="elf-count"><?= $familyCount[1] ?></p>
                        </div>
                    </div>
                    <div class="half right">
                        <div class="race-item" data-tip="Viles">
                            <span class="icon vail"></span>
                            <p id="vail-count"><?= $familyCount[2] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Race END -->
        </div>

        <table style='width: 100%; margin-top: 20px;'>
            <tr>
                <th></th>
                <th>Username</th>
                <th>Nickname</th>
                <th>Job</th>
                <th>Level</th>
                <th>Map</th>
                <th>Kills</th>
                <th>Deaths</th>
            </tr>
            <?php
            $query = "SELECT c.CharName, c.Job, c.Level, c.Map, c.K1, c.K2, u.Country, c.CharID, c.UserUID, c.UserID FROM PS_GameData.dbo.Chars c JOIN PS_GameData.dbo.UserMaxGrow u ON c.UserUID = u.UserUID WHERE c.LoginStatus = 1 $condition";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $Map = getMapName($row['Map']);
                echo "<tr class='text-center'>";
                echo "<td><div class='faction_icon {$faction_icon[$row['Country']]}'></div></td>";
                echo "<td><a href='/?p=gm-panel-n3w&sp=user-search&CharID={$row['CharID']}'>{$row['UserID']}</a></td>";
                echo "<td>{$row['CharName']}</td>";
                echo "<td><div class='faction_icon {$job_icon[$row['Job']]}'></div></td>";
                echo "<td>{$row['Level']}</td>";
                echo "<td>$Map</td>";
                echo "<td>{$row['K1']}</td>";
                echo "<td>{$row['K2']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
