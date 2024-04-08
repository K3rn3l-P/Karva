<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

    <?php
if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {
    return;
}
$uid = $_GET["uid"];

// Try to find item in inventory
$stmt = $conn->prepare("SELECT I.ItemName, I.Slot AS SlotCount, CI.* FROM PS_GameData.dbo.CharItems CI 
                        LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID
                        WHERE ItemUID=:uid");
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Try to find it in warehouse
if (!$item) {
    $stmt = $conn->prepare("SELECT I.ItemName, I.Slot AS SlotCount, CI.* FROM PS_GameData.dbo.UserStoredItems CI 
                            LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID
                            WHERE ItemUID=:uid");
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    // Not found
    if (!$item) {
        echo "<h2 style='color: red;'>Item not found</h2>";
        return;
    }
}

// Initialize array for gems
$gems = [0 => "Empty"];

// Fetch gems from the database
$stmt = $conn->prepare("SELECT TypeID, ItemName FROM PS_GameDefs.dbo.Items WHERE Type=30 AND Count>0");
$stmt->execute();
while ($gem = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $gems[$gem['TypeID']] = $gem['ItemName'];
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