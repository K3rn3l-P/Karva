<table>
	<tr>
		<th>Item</th>
		<th>DT</th>
		<th>Info</th>
		<th>From/Count</th>
		<th>Action</th>
	</tr>
	<?php
	$stmt = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE CharID=:charID AND ActionType=112 AND Text2 NOT IN ('use_item','etin_return') ORDER BY ActionTime DESC");
	$stmt->bindParam(':charID', $CharID, PDO::PARAM_INT);
	$stmt->execute();
	
	while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$ItemUID = $Item["Value1"];
		
		// Item raised
		$raiseStmt = $conn->prepare("SELECT 1 FROM PS_GameLog.dbo.ActionLog WHERE ActionTime BETWEEN :actionTimeStart AND DATEADD(MINUTE, 5, :actionTimeStart) AND Value1=:itemUID AND ActionType=111");
		$actionTimeStart = $Item['ActionTime'];
		$raiseStmt->bindParam(':actionTimeStart', $actionTimeStart);
		$raiseStmt->bindParam(':itemUID', $ItemUID);
		$raiseStmt->execute();
		
		if ($raiseStmt->rowCount()) {
			continue;
		}
		
		// Item exists
		$checkStmt1 = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID=:itemUID");
		$checkStmt1->bindParam(':itemUID', $ItemUID);
		$checkStmt1->execute();
		
		$checkStmt2 = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID=:itemUID");
		$checkStmt2->bindParam(':itemUID', $ItemUID);
		$checkStmt2->execute();
		
		$checkStmt3 = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID=:itemUID");
		$checkStmt3->bindParam(':itemUID', $ItemUID);
		$checkStmt3->execute();
		
		if ($checkStmt1->rowCount() || $checkStmt2->rowCount() || $checkStmt3->rowCount()) {
			continue;
		}
		
		include("item.php");
	}
	?>
</table>
