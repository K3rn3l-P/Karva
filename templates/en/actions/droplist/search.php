<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
$itemID = isset($_POST['i']) ? $_POST['i'] : 0;

if ($itemID == 0) {
    echo '<h1 style="text-align: center; margin: 50px;">You need to select an item for continue</h1>';
	return;
} 

$search = $conn->prepare("SELECT mn.MapName, mn.MapID
		FROM PS_GameDefs.dbo.MobItems mi 
		INNER JOIN PS_GameDefs.dbo.Mobs m ON m.MobID = mi.MobID 
		INNER JOIN PS_GameDefs.dbo.MapNames mn ON m.MapID = mn.MapID
		INNER JOIN PS_GameDefs.dbo.Items i ON mi.Grade = i.Grade
		WHERE mi.MobID != 0 AND i.ItemID=$itemID GROUP BY mn.MapName, mn.MapID ORDER BY mn.MapID ASC");
$search->execute();

echo '<table class="drops">
	<tr>
		<th style="width:200px;">MapName</th>
		<th style="width: 544px;">Mobs</th>
	</tr>';
$rowID = 0;
while ($s = $search->fetch(PDO::FETCH_NUM)) {
	echo '<tr>
	<td style="text-align:center;">' . $s[0] . '</td>';
	$mobs = $conn->prepare("SELECT m.MobName, m.Level, mi.ItemOrder, mi.DropRate
				FROM PS_GameDefs.dbo.MobItems mi 
				INNER JOIN PS_GameDefs.dbo.Mobs m ON m.MobID = mi.MobID 
				INNER JOIN PS_GameDefs.dbo.MapNames mn ON m.MapID = mn.MapID
				INNER JOIN PS_GameDefs.dbo.Items i ON mi.Grade = i.Grade
				WHERE mi.MobID != 0 AND mi.DropRate > 0 AND i.ItemID=$itemID AND m.MapID = " . $s[1] . " ORDER BY mi.DropRate DESC");
	$mobs->execute();

	echo '<td>
		<table class="mobs">';
	if ($rowID == 0) {
		echo '<tr>
				<th style="width:300px;">Name</th>
				<th style="width:122px;">Level</th>
				<th style="width:122px;">Drop Rate</th>
			</tr>';
	}
	$rowID = 1;
	while ($mob = $mobs->fetch(PDO::FETCH_NUM)) {
		$droprate = $mob[3];
		if ($mob[2] > 6) {
			$droprate = $droprate / 10000;
		}
		echo '<tr><td style="width:300px;text-align:left;">' . $mob[0] . '</td><td style="width:122px;">' . $mob[1] . '</td><td style="width:122px;">' . $droprate . '%</td></tr>';
	}
	echo '</table>
		</td>
		</tr>';
}
echo '</table>';
	