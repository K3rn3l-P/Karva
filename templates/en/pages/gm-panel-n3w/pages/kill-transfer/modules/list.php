<table class="table-center">
	<tr>
		<th>CharID</th>
		<th>CharName</th>
		<th>Job</th>
		<th>Kills</th>
		<th>Deaths</th>
		<th></th>
	</tr>
	<?php
	foreach ($Chars as $Char) {
		include("char.php");
	}
	?>
</table>