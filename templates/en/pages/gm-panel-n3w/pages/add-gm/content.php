<?php if (!isset($UserUID) || !$UserUID) die ?>

<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->


<?php
$command = $conn->prepare($query);
$command->bindParam(1, $val, PDO::PARAM_INT);
$command->execute();
$result = $command->fetch(PDO::FETCH_NUM);

		if (isset($_POST['submit'])) {
			if (empty ($_POST['CharName'])) {
				echo "<div id='spiega'>";
				header("refresh: 2;url=player");
				die('You didn\'t specify a Character Name!');
				echo "</div>";
			} else {
				$char = $_POST['CharName'];
				$queryName = $conn->prepare ('SELECT UserUID, CharID FROM PS_GameData.dbo.Chars WHERE CharName= ?');
				$queryName->bindParam(1, $char, PDO::PARAM_INT);
				$queryName->execute();
				$charUID = $queryName->fetch(PDO::FETCH_NUM);
				if ($charUID[0] == NULL) {
					die ('Account not founded! Please Insert Character name!');
				} else{
					if (!empty($_POST['action'])){
						$action = $_POST['action'];
						$actionValue = getClear($_POST['actionValue']);
						switch ($action){
							case 1:
								$queryAction=$conn->prepare("UPDATE PS_GameData.dbo.Chars SET CharName = '".$actionValue."' WHERE CharID= ?");
								$queryAction->bindParam(1, $charUID[1], PDO::PARAM_INT);
								$queryAction->execute();
								
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Nickname changing', 'NEWNAME: $actionValue; UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								echo "Name Changed!";
								break;
							
							
						
							case 4:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "GMA Function Added - Status 48";

								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status= '48' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();

								$queryAction=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'GMA Function Added - Status 48', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
								
							
							
							
							case 5:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "GM PANEL FUNCTION Added";

								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET AdminLevel= '201' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();

								$queryAction=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'GM PANEL FUNCTION Added', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							
							
							
							case 6:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "GM STATUS REMOVED / THIS INCLUDE GM PANEL FUNCTION REMOVED ALSO!";

								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status= '0', AdminLevel= '0' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();

								$queryAction=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'GM STATUS & PANEL REMOVED', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
								
							case 7:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "HIDE ACCOUNT FROM SEARCH LOGS";

								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET AdminLevel= '1' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();

								$queryAction=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'HIDE ACCOUNT FROM SEARCH LOGS', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;	


							case 8:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "GM Function Added - Status 32";

								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status= '32' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();

								$queryAction=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'GM Function Added - Status 32', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;







						}
					}
				}
			}
		}
$queryKick = $conn->prepare("EXEC [PS_GameDefs].[dbo].[Command] @serviceName = N'ps_game', @cmmd = N'/kickuid $charUID[0]'");
$queryKick->bindParam(1, $result[0], PDO::PARAM_INT);
$queryKick->execute();		
?>
		
		
		
		
		
		
		
		
		
		
		<div id="spiega">
			<form method="POST">
				<table>
					<tr><td>Character Name:</td><td><input type="text" name="CharName"/></td></tr>
					<tr><td>Actions:</td><td><select name="action">
								<option value="999"> </option>

								<?php if ($UserInfo["AdminLevel"] >= 255) : ?>
									<option value="4">Add GMA - Status 48</option>
									<option value="8">Add GM - Status 32</option>
									<option value="5">Add GM - PANEL FUNCTION</option>
									<option value="6">Remove GM/GMA</option>
									
								<?php endif ?>

							</select></td></tr>
				
				</table>

				<p><center><input type="submit" value="Submit" name="submit" ></center></p>
			</form>
		</div>
		<br>
		<br>
		
		<!-- end content -->		

    </div>
</div>