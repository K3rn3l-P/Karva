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
	$SqlRes = odbc_exec($odbcConn, "SELECT I.ItemName, CI.* FROM PS_GameData.dbo.CharItems CI LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID WHERE CharID={$CharID} ORDER BY CI.Bag,CI.Slot");
	while ($Item = odbc_fetch_array($SqlRes)) {
		include("item.php");
	}
	?>
</table>