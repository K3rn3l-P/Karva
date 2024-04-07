<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
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
		$date = date("Y-m-d G:i", time());
		$queryAccount2 = $conn->prepare('SELECT * FROM PS_Website.dbo.Payments ORDER BY Row DESC');
		$queryAccount2->execute();


		echo "<table id='control' style='width:692px;'>";

		echo "<tr>
					<th>UserUID</th>
					<th>SP </th>
					<th>Method </th>
					<th>Date</th>
					</tr>";
		while ($charAccount2 = $queryAccount2->fetch(PDO::FETCH_NUM)) {
			$queryAccount1 = $conn->prepare('SELECT UserID FROM PS_UserData.dbo.Users_Master WHERE UserUID= ? ');
			$queryAccount1->bindParam(1, $charAccount2[1], PDO::PARAM_INT);
			$queryAccount1->execute();
			$charAccount1 = $queryAccount1->fetch(PDO::FETCH_NUM);
			echo "<tr>
					
					<td>" . $charAccount2[2] . "</td>
					<td>" . $charAccount2[3] . "</td>
					<td>" . $charAccount2[4] . "</td>
					<td>" . $charAccount2[5] . "</td>
					</tr>";
		}
		echo "</table>";
		
		
		odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
				VALUES ($UserUID, '$UserID', 'Access to payment logs', '', '$UserIP')");
		?>
		<!-- end content -->	

    </div>
</div>