<table>
	<tr>
		<th>Item</th>
		<th>DT</th>
		<th>Info</th>
		<th>From/Count</th>
		<th>Action</th>
	</tr>
	<?php
	
	$stmt = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE CharID=$CharID AND ActionType=112 AND Text2 NOT IN ('use_item','etin_return') ORDER BY ActionTime DESC");
	$stmt->execute();	
	while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$ItemUID = $Item["Value1"];
		// Item raised
		$Res = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameLog.dbo.ActionLog WHERE ActionTime BETWEEN '$Item[ActionTime]' AND DATEADD(MINUTE,5,'$Item[ActionTime]') AND Value1=$ItemUID AND ActionType=111");
		if (odbc_num_rows($Res)) 
			continue;
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