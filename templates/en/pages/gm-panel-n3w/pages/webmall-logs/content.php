<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<center><p style="color:orange">IMPORTANT: <br>1. Logs informations apply only for items that no longer via Bank Teller! <br>2. If Items are still on Bank Teller, logs will not display that items!</p></center>
		<div id="spiega">
			<form method="POST">
				<table>
					<tr>
						<td>Account Name:</td>
						<td><input type="text" name="UserID"/></td>
					</tr>
					<tr>
						<td>Character Name:</td>
						<td><input type="text" name="CharName"/></td>
					</tr>
				</table>
				<p><input type="submit" value="Submit" name="submit" style="margin: 10px 0 0 320px;"/></p>
			</form>
		</div>
		<style>
			/* Tables */
			table {
				border-collapse: collapse;
				padding: 0;
				margin: 0 auto;
				border: solid 1px #e87308;
			}

			td {
				border: solid 1px #653508;
			}

			td .form-item {
				margin: 5px 0;
			}

			table th {
				font-size: 12px;
				font-weight: bold;
				background-color: #e87308;;
				text-align: left;
				padding: 7px 10px;
				border: solid 1px #e87308;
				font-family: Tahoma, Geneva;

				background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#2E2E2E), to(#1D1D1D));
				filter: Progid:DXImageTransform.Microsoft.gradient(startColorstr=#2E2E2E, endColorstr=#1D1D1D)
			}

			border {
				color: red;
			}
		</style>

		<?php
		$query = "";
		$val = "";
		if (isset($_POST["UserID"]) && !empty($_POST["UserID"])) {
			$val = $_POST["UserID"];
			$query = "SELECT UserUID FROM PS_UserData.dbo.Users_Master WHERE UserID=? AND AdminLevel <= 254";
		} elseif (isset($_POST["CharName"]) && !empty($_POST["CharName"])) {
			$val = $_POST["CharName"];
			$query = "SELECT UserUID FROM PS_UserData.dbo.Users_Master WHERE UserUID=(SELECT TOP 1 UserUID FROM PS_GameData.dbo.Chars WHERE CharName=? ORDER BY Del) AND AdminLevel <= 254";
		}
		?>
		<?php
		if ($query){		
		$date = date("Y-m-d G:i", time());
		$userIdQuery = $conn->prepare($query);
		$userIdQuery->bindParam(1, $val, PDO::PARAM_INT);
		$userIdQuery->execute();
		$userId = $userIdQuery->fetch(PDO::FETCH_NUM)[0];

		$queryLog = $conn->prepare('SELECT * FROM PS_GameData.dbo.ProductLog WHERE UserUID = ? ORDER BY RowID DESC');
		$queryLog->bindParam(1, $userId, PDO::PARAM_INT);
		$queryLog->execute();


		echo "<table id='control' style='width:692px;'>";

		echo "<tr>
					<th>UserUID</th>
					<th>CharID </th>
					<th>ItemID </th>
					<th>ItemCount </th>
					<th>Category </th>
					<th>Date</th>
					</tr>";
		while ($charAccount2 = $queryLog->fetch(PDO::FETCH_NUM)) {
			echo "<tr>
					
					<td>" . $charAccount2[2] . "</td>
					<td>" . $charAccount2[3] . "</td>
					<td>" . $charAccount2[4] . "</td>
					<td>" . $charAccount2[7] . "</td>
					<td>" . $charAccount2[8] . "</td>
					<td>" . $charAccount2[11] . "</td>
					</tr>";
		}
		echo "</table>";
		
		
		odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
				VALUES ($UserUID, '$UserID', 'Access to Web Mall logs', '', '$UserIP')");
		}
		?>
		<!-- end content -->	

    </div>
</div>