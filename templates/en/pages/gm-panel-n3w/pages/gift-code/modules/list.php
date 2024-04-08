<table class="table-center" style="width: 100%">
	<tr>
		<th>ID</th>
		<th>Code</th>
		<th>FEU</th>
		<th>ItemID</th>
		<th>Count</th>
		<th>SP</th>
		<th>EndDate</th>
		<th></th>
	</tr>
	<?php
	$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE Del=0 ORDER BY ID DESC");
	$stmt->execute();
	while ($GiftCode = $stmt->fetch(PDO::FETCH_ASSOC)) {
		include("code.php");
	}
	?>
</table>