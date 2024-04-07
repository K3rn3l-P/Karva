<table class="table-center text-center" style="width: 100%;">
	<tr>
		<th class="marketid-column">MarketID</th>
		<th class="userid-column">UserID</th>
		<th class="charname-column">CharName</th>
		<th class="item-column" style="width: 180px">Item</th>
		<th class="count-column" style="width: 50px">Count</th>
		<th class="iteminfo-column">Item info</th>
		<th class="minmoney-column">Min money</th>
		<th class="buymoney-column">Buy money</th>
		<th class="bet-column">Bet</th>
		<th class="enddate-column">End date</th>
		<th class="status-column">Status</th>
	</tr>
	<?php
	while ($row = odbc_fetch_array($SqlRes)) {
		include("item.php");
	}
	?>
</table>