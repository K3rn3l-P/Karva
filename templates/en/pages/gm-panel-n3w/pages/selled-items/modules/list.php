<table>
	<tr>
		<th>DT</th>
		<th>Item</th>
		<th>Count</th>
		<th>Info</th>
		<th>From</th>
		<th>Action</th>
	</tr>
	<?php
	$stmt = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE CharID=$CharID AND ActionType=114 ORDER BY ActionTime DESC");
	$stmt->execute();
	while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$ItemUID = $Item["Value1"];
		// Item exists
		$SqlRes1 = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID=$ItemUID");
		$SqlRes2 = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID=$ItemUID");
		$SqlRes3 = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID=$ItemUID");
		if (odbc_num_rows($SqlRes1) || odbc_num_rows($SqlRes2) || odbc_num_rows($SqlRes3)) 
			continue;
		
		include("item.php");
	}
	?>
</table>