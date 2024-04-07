<table>
	<tr>
		<th>QuestID</th>
		<th>Count1</th>
		<th>Count2</th>
		<th>Count3</th>
		<th>Success</th>
		<th>Finish</th>
		<th>Action</th>
	</tr>
	<?php
	$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.CharQuests WHERE CharID=$CharID AND Del=0");
	while ($Item = odbc_fetch_array($SqlRes)) {
		$rowId = $Item["RowID"];
		include("item.php");
	}
	?>
</table>