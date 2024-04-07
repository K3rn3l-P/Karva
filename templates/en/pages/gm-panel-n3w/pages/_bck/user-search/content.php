<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>ADMIN-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<style>
			#control {
				border: 1px solid #000;
				float: left;
				margin-left: 10px;
				font-size: 10px;
				margin-top: 10px;
			}

			#control td {
				border: 1px solid #000;
				padding: 5px;
				font-weight: 600;
			}
		</style>

		<form method="GET">
			<input type="hidden" name="p" value="gm-panel-n3w" />
			<input type="hidden" name="sp" value="user-search" />
			<span class="orange" style="width: 17%; display: inline-block;">Account Name: </span><input type="text" name="UserID"/>
			 <br />
			<span class="orange" style="width: 17%; display: inline-block;">Character Name: </span><input type="text" name="CharName"/>
			 <br />
			<span class="orange" style="width: 17%; display: inline-block;">UserUID: </span><input type="text" name="UserUID"/>
			 <br />
			<span class="orange" style="width: 17%; display: inline-block;">CharID: </span><input type="text" name="CharID"/>
			 <br />
			<span class="orange" style="width: 17%; display: inline-block;">IP: </span><input type="text" name="UserIP"/>
			<p><input type="submit" value="Submit" style="margin: 15px 0 0 125px;"/></p>
		</form>
		<?
		$query = "";
		$val = "";
		if (isset($_GET["UserID"]) && !empty($_GET["UserID"])) {
			$val = $_GET["UserID"];
			$query = "SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserID=?";
			// Log action				
			odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
					VALUES ($UserUID, '$UserID', '[User-Search] By UserID', 'VALUE: $val', '$UserIP')");
		} elseif (isset($_GET["UserUID"]) && !empty($_GET["UserUID"])) {
			$val = $_GET["UserUID"];
			$query = "SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserUID=?";
			// Log action				
			odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
					VALUES ($UserUID, '$UserID', '[User-Search] By UserUID', 'VALUE: $val', '$UserIP')");
		} elseif (isset($_GET["CharID"]) && !empty($_GET["CharID"])) {
			$val = $_GET["CharID"];
			$query = "SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserUID=(SELECT TOP 1 UserUID FROM PS_GameData.dbo.Chars WHERE CharID=?)";
			// Log action				
			odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
					VALUES ($UserUID, '$UserID', '[User-Search] By CharID', 'VALUE: $val', '$UserIP')");
		} elseif (isset($_GET["CharName"]) && !empty($_GET["CharName"])) {
			$val = $_GET["CharName"];
			$query = "SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserUID=(SELECT TOP 1 UserUID FROM PS_GameData.dbo.Chars WHERE CharName=? ORDER BY Del)";
			// Log action				
			odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
					VALUES ($UserUID, '$UserID', '[User-Search] By CharName', 'VALUE: $val', '$UserIP')");
		} elseif (isset($_GET["UserIP"]) && !empty($_GET["UserIP"])) {
			$val = $_GET["UserIP"];
			$query = "SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserUID in (SELECT DISTINCT UserUID FROM PS_GameLog.dbo.ActionLog WHERE text1=? AND actiontype= 107)";
			// Log action				
			odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
					VALUES ($UserUID, '$UserID', '[User-Search] By IP', 'VALUE: $val', '$UserIP')");
		}

		if ($query) {
			$queryUserID = $conn->prepare($query);
			$queryUserID->bindParam(1, $val, PDO::PARAM_INT);
			$queryUserID->execute();
			while($userDetails = $queryUserID->fetch(PDO::FETCH_NUM)) {
				include('modules/user.php');				
			}
		}

		?>
		<!-- end content -->	

    </div>
</div>