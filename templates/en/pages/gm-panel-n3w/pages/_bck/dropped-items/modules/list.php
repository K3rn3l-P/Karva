<table>
	<tr>
		<th>Item</th>
		<th>DT</th>
		<th>Info</th>
		<th>From/Count</th>
		<th>Action</th>
	</tr>
	<?php
	$query = "SELECT * FROM PS_GameLog.dbo.ActionLog WHERE CharID=:CharID AND ActionType=112 AND Text2 NOT IN ('use_item','etin_return') ORDER BY ActionTime DESC";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':CharID', $CharID, PDO::PARAM_INT);
	$stmt->execute();	
	while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$ItemUID = $Item["Value1"];
		// Item raised
		$queryRes = "SELECT 1 FROM PS_GameLog.dbo.ActionLog WHERE ActionTime BETWEEN :ActionTime AND DATEADD(MINUTE,5,:ActionTime) AND Value1=:ItemUID AND ActionType=111";
		$stmtRes = $conn->prepare($queryRes);
		$stmtRes->bindParam(':ActionTime', $Item['ActionTime'], PDO::PARAM_STR);
		$stmtRes->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
		$stmtRes->execute();
		if ($stmtRes->rowCount() > 0) 
			continue;
		// Item exists
		$querySql1 = "SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID=:ItemUID";
		$stmtSql1 = $conn->prepare($querySql1);
		$stmtSql1->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
		$stmtSql1->execute();

		$querySql2 = "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID=:ItemUID";
		$stmtSql2 = $conn->prepare($querySql2);
		$stmtSql2->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
		$stmtSql2->execute();

		$querySql3 = "SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID=:ItemUID";
		$stmtSql3 = $conn->prepare($querySql3);
		$stmtSql3->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
		$stmtSql3->execute();
		
		if ($stmtSql1->rowCount() > 0 || $stmtSql2->rowCount() > 0 || $stmtSql3->rowCount() > 0) 
			continue;
		
		include("item.php");
	}
	?>
</table>
