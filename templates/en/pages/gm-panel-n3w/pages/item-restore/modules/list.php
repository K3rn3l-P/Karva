<table>
	<tr>
		<th>UID</th>
		<th>UserID</th>
		<th>CharName</th>
		<th>Item</th>
		<th>Date</th>
		
		<th>By</th>
		<th>Action</th>
	</tr>
	<?php
	$stmt = $conn->prepare("SELECT TOP 100 * FROM PS_GameLog.dbo.BrokenItems WHERE Res=0 ORDER BY DT DESC");
	$stmt->execute();	
	while ($Item = $stmt->fetch(PDO::FETCH_ASSOC)) {
		include("item.php");
	}
	?>
</table>