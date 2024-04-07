<table class="table-center">
	<tr>
		<th>Bag</th>
		<th>Slot</th>
		<th>Item</th>
		<th>Count</th>
		<th>Lapis</th>
		<th>Craftname</th>
		<th></th>
	</tr>
	<?php
	
	$stmt = $conn->prepare("SELECT I.ItemName, CI.* FROM PS_GameData.dbo.CharItems CI LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID WHERE CharID={$Char["CharID"]} ORDER BY CI.Bag,CI.Slot");
	$stmt->execute();		
	while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
		include("item.php");
	}
	?>
</table>