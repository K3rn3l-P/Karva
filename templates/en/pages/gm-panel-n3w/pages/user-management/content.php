<?php if (!isset($UserUID) || !$UserUID) die ?>

<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<?php

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
								$queryAction=$conn->prepare("UPDATE PS_GameData.dbo.Chars SET CharName = '".$actionValue."' WHERE CharID= ?");
								$queryAction->bindParam(1, $charUID[1], PDO::PARAM_INT);
								$queryAction->execute();
								
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Nickname changing', 'NEWNAME: $actionValue; UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								echo "Name Changed!";
								break;
							case 3:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								$queryAction=$conn->prepare("
		USE PS_GameData

		DECLARE @CharID INT = ?,
				@ItemID INT = ?,
				@ItemCount TINYINT = 1,
				@MinBag TINYINT = 1,
				@MinSlot TINYINT = 0

		IF (SELECT COUNT(*) FROM CharItems WHERE CharID = @CharID AND Bag != 0) >= 120
		BEGIN
			
			PRINT 'The character''s inventory is full.'
			RETURN
			
		END

		WHILE @MinBag <= 5
		BEGIN
			
			WHILE @MinSlot <= 23
			BEGIN
				
				IF NOT EXISTS (SELECT * FROM CharItems WHERE CharID = @CharID AND Bag = @MinBag AND Slot = @MinSlot)
				BEGIN
					
					INSERT INTO CharItems (CharID, ItemID, ItemUID, Type, TypeID, Bag, Slot, Quality, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, Craftname, Count, Maketime, Maketype, Del)
					VALUES (@CharID, @ItemID, dbo.ItemUID(), @ItemID / 1000, @ItemID % 1000, @MinBag, @MinSlot, (SELECT Quality FROM PS_GameDefs.dbo.Items WHERE ItemID = @ItemID), 0, 0, 0, 0, 0, 0, '00000000000000000000', @ItemCount, GETDATE(), 'S', 0)
					
					IF @@ERROR = 0 AND @@ROWCOUNT = 1
						PRINT 'Item inserted successfully in Bag ' + CAST(@MinBag AS VARCHAR(1)) + ', Slot ' + CAST(@MinSlot AS VARCHAR(2)) + '.'
					ELSE PRINT 'An error occured while attempting to insert the item.'
					
					RETURN
					
				END
				
				SET @MinSlot += 1
				
			END
			SET @MinSlot = 0
			SET @MinBag += 1
			
		END");
								$queryAction->bindParam(1, $charUID[1], PDO::PARAM_INT);
								$queryAction->bindParam(2, $actionValue, PDO::PARAM_INT);
								$queryAction->execute();										
								echo "Item successfully added to inventory!";
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Adding item to inventory', 'ITEMID: $actionValue; UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							case 4:
								echo "Account Unlocked!";
								$queryAction=$conn->prepare("UPDATE PS_GameData.dbo.Chars SET LoginStatus= '0' WHERE CharID= ?");
								$queryAction->bindParam(1, $charUID[1], PDO::PARAM_INT);
								$queryAction->execute();
								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Leave= '0', LeaveDate= '2021-02-19 21:26:00' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Unlock account', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							case 5:
								echo "Account Unbaned!";

								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status= '0' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();

								$queryAction=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Unban account', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							case 6:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "Shaiya Points added!";
								$queryAction=$conn->prepare("Select Point FROM PS_UserData.dbo.Users_Master WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								$point = $queryAction->fetch(PDO::FETCH_NUM);
								$newPoint = $point[0] + $actionValue;
								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point= '".$newPoint."' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Add points', 'POINTS: $actionValue; UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							case 7:
								echo "Reset Kills Changed to 0!";
								$queryAction=$conn->prepare("UPDATE PS_GameData.dbo.Chars SET K1 = 0 , K2 = 0  WHERE UserUID = ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Reset kills', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							case 8:
								echo "Player completly moved in Auction House!";
								$queryAction=$conn->prepare("UPDATE PS_GameData.dbo.Chars SET Map = 42 , PosX = 62.204 , PosY = 2 , Posz = 54.291  WHERE UserUID = ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Move to AH', 'UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;
							case 9:
								if ($UserInfo["AdminLevel"] < 255)
									return;
								echo "Shaiya Points Removed!";
								$queryAction=$conn->prepare("Select Point FROM PS_UserData.dbo.Users_Master WHERE UserUID= ?");
								$queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction->execute();
								$point = $queryAction->fetch(PDO::FETCH_NUM);
								$newPoint = $point[0] - $actionValue;
								$queryAction1=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point= '".$newPoint."' WHERE UserUID= ?");
								$queryAction1->bindParam(1, $charUID[0], PDO::PARAM_INT);
								$queryAction1->execute();
								
								// Log action
								odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
										VALUES ($UserUID, '$UserID', 'Del points', 'POINTS: $actionValue; UID: $charUID[0]; CID: $charUID[1]', '$UserIP')");
								break;

						}
					}
				}
			}
		}
		?>
		<div id="spiega">
			<form method="POST">
				<table>
					<tr><td>Character Name:</td><td><input type="text" name="CharName"/></td></tr>
					<tr><td>Actions:</td><td><select name="action">
								<option value="999"> </option>
								<option value="1">Rename</option>
								<option value="7">Reset Kills</option>

								<option value="4">Unlock Account</option>
								<option value="5">UnBan Account</option>
								<option value="8">Move player in AH</option>
								<?php if ($UserInfo["AdminLevel"] >= 255) : ?>
									<option value="3">Add Item to inventory</option>
									<option value="6">Add Shaiya Points</option>
									<option value="9">Remove Shaiya Points</option>
								<?php endif ?>

							</select></td></tr>
					<tr><td>Second Value of Action:</td><td><input type="text" name="actionValue"/></td></tr>
				</table>

				<p><input type="submit" value="Submit" name="submit" style="margin: 30px 0 0 470px;"/></p>
			</form>
		</div>
		<br>
		<br>
		<table>
			<tr><th>Second Value Instructions</th></tr>
			<tr>
				<td><b>Rename:</b> Insert the new character name in the second Value.</td>
			</tr>
			<tr>
				<td><b>Reset Kills:</b> Insert the value 0 in the second Value, this function usable for Stat Padders.</td>
			</tr>
			<tr>
				<td><b>Reset Tiered Spender:</b> Insert the value 0 in the second Value.</td>
			</tr>
			<tr>
				<td><b>Add item to inventory:</b> Insert the ItemID of item to add (this will give only 1 item)</td>
			</tr>
			<tr>
				<td><b>Unlock Blocked player:</b> Insert the value 0 in the second Value, this will unlock a account that can't log.</td>
			</tr>
			<tr>
				<td><b>Unban Banned player:</b> Insert the value 0 in the second Value, this will unban a account that is banned.</td>
			</tr>
			<tr>
				<td><b>Move Player in AH:</b> Apply this only for people who is getting Character data error.</td>
			</tr>
			<tr>
				<td><b>Add Shaiya Points:</b> Insert the SP that you want give.</td>
			</tr>
			<tr>
				<td><b>Remove Shaiya Points:</b> Insert the SP that you want to remove.</td>
			</tr>
			<tr><th><b>NOTE:</b> "all commands works by Character name only"</th></tr>
		</table>
		<!-- end content -->	

    </div>
</div>