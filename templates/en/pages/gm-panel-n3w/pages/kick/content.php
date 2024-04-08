
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
					die ('account not found');
				} else{
					if (!empty($_POST['action'])){
						$action = $_POST['action'];
						$actionValue = getClear($_POST['actionValue']);
						switch ($action){
							
							
							case 1:
								echo "Kick/unlock stacked player";
								$queryAction=$conn->prepare("UPDATE PS_GameData.dbo.Chars SET LoginStatus= '0' WHERE CharID= ?");
								$queryAction->bindParam(1, $charUID[1], PDO::PARAM_INT);
								$queryAction->execute();
								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Leave= '0', LeaveDate= '2017-08-20 21:26:00' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Kick/unlock stacked player', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
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
								
								<option value="1">kick</option>
								

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