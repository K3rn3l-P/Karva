<table class="table-center text-center" style="width: 100%;">
	<tr>
		<th class="charname-column <?= $charId || $userUid ? "hidden" : "" ?>"><?= $isWarehouse ? "UserID" : "CharName" ?></th>
		<th class="itemuid-column <?= $itemUid ? "hidden" : "" ?>">ItemUID</th>
		<th class="bag-column" style="width: 80px">Bag</th>
		<th class="slot-column" style="width: 80px">Slot</th>
		<th class="item-column" style="width: 230px">Item</th>
		<th class="count-column" style="width: 50px">Count</th>
		<th class="iteminfo-column">Item info</th>
		<th class="gems-column">Gems</th>
		<th class="maketime-column">Maketime</th>
		<th class="action-column">Actions</th>
	</tr>
	<?php
	while ($row = odbc_fetch_array($SqlRes)) {
		include("item.php");
	}
	?>
</table>