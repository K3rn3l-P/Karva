<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

        <?php
		odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
				VALUES ($UserUID, '$UserID', 'Access to users-online page', '', '$UserIP')");
				
		$faction = isset($_GET["faction"]) ? $_GET["faction"] : -1;
        switch ($faction) {
            case 0:
                $condition = 'AND u.Country = 0';
                break;
            case 1:
                $condition = 'AND u.Country = 1';
                break;
            default:
                $condition = ' ';
                break;
        }

		// Get faction count of players
		$factionCount = array(0 => 0, 1 => 0, 2 => 0);
		$query = "SELECT UMG.Country, COUNT(1) AS [Cnt] FROM PS_GameData.dbo.Chars C 
				LEFT JOIN PS_GameData.dbo.UserMaxGrow UMG ON UMG.UserUID=C.UserUID
				WHERE C.LoginStatus=1
				GROUP BY UMG.Country";
		$odbcResult = odbc_exec($odbcConn, $query);
		while ($item = odbc_fetch_array($odbcResult)) {
			$country = $item["Country"];
			$factionCount[$country] = $item["Cnt"];
		}
		$total = $factionCount[0] + $factionCount[1];
				
		// Get family count of players
		$familyCount = array(0 => 0, 1 => 0, 2 => 0, 3 => 0);		
		$result = odbc_exec($odbcConn, "SELECT Family FROM PS_GameData.dbo.Chars WHERE LoginStatus=1");
		while (odbc_fetch_row($result)) {
			$family = odbc_result($result, "Family");
			$familyCount[$family]++;
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
		
		
        <table  style='width: 100%; margin-top: 20px;'>
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
            $command = $conn->prepare('SELECT c.CharName, c.Job, c.Level, c.Map, c.K1, c.K2, u.Country, c.CharID, c.UserUID, c.UserID FROM PS_GameData.dbo.Chars c JOIN PS_GameData.dbo.UserMaxGrow u ON c.UserUID = u.UserUID WHERE c.LoginStatus = 1' . $condition);
            $command->execute();
            while ($result = $command->fetch(PDO::FETCH_NUM)) {
				$job = $result[1];
				$faction = $result[6];

                $Map = getMapName($result[3]);

                echo "<tr class='text-center'>";
					echo "<td><div class='faction_icon $faction_icon[$faction]'></div></td>";
					echo "<td><a href='/?p=gm-panel-n3w&sp=user-search&CharID=$result[7]'>" . $result[9] . "</a></td>";
					echo "<td>" . $result[0] . "</td>";
					echo "<td><div class='faction_icon $job_icon[$job]'></div></td>";
					echo "<td>" . $result[2] . "</td>";
					echo "<td>" . $Map . "</td>";
					echo "<td>" . $result[4] . "</td>";
					echo "<td>" . $result[5] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

    </div>
</div>