<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<?php
		$query = "";
		$val = "";
		if (isset($_POST["CharName"]) && !empty($_POST["CharName"])) {
			$val = $_POST["CharName"];
			$query = "SELECT c.CharID FROM PS_GameData.dbo.Chars AS c WHERE c.charName=? AND c.Del = 1";
		}
		
		if ($query) 
			include("modules/handle-request.php");
		?>

		<div id="spiega">
			<form method="POST">
				<table>
					<tr>
						<td>Character Name:</td>
						<td><input type="text" name="CharName"/></td>
					</tr>
				</table>
				<p><input type="submit" value="Submit" name="submit" style="margin: 10px 0 0 320px;"/></p>
			</form>
		</div>
		<!-- end content -->	

    </div>
</div>