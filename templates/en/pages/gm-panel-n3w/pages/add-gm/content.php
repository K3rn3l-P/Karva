<?php if (!isset($UserUID) || !$UserUID) die ?>

<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->


		<?php
if (isset($_POST['submit'])) {
    if (empty($_POST['CharName'])) {
        echo "<div id='spiega'>";
        header("refresh: 2;url=player");
        die('You didn\'t specify a Character Name!');
        echo "</div>";
    } else {
        $char = $_POST['CharName'];
        $queryName = $conn->prepare('SELECT UserUID, CharID FROM PS_GameData.dbo.Chars WHERE CharName = ?');
        $queryName->bindParam(1, $char, PDO::PARAM_STR);
        $queryName->execute();
        $charUID = $queryName->fetch(PDO::FETCH_NUM);
        if ($charUID[0] === NULL) {
            die('Account not found! Please insert a Character name!');
        } else {
            if (!empty($_POST['action'])) {
                $action = $_POST['action'];
                $actionValue = getClear($_POST['actionValue']);
                switch ($action) {
                    case 1:
                        $queryAction = $conn->prepare("UPDATE PS_GameData.dbo.Chars SET CharName = ? WHERE CharID = ?");
                        $queryAction->bindParam(1, $actionValue, PDO::PARAM_STR);
                        $queryAction->bindParam(2, $charUID[1], PDO::PARAM_INT);
                        $queryAction->execute();
                        $logText = "NEWNAME: $actionValue; UID: $charUID[0]; CID: $charUID[1]";
                        $logAction = "Nickname changing";
                        break;
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        if ($UserInfo["AdminLevel"] < 255) {
                            return;
                        }
                        $status = ($action === 4 || $action === 8) ? 32 : 48;
                        $adminLevel = ($action === 5) ? 201 : 0;
                        $queryAction1 = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status = ?, AdminLevel = ? WHERE UserUID = ?");
                        $queryAction1->bindParam(1, $status, PDO::PARAM_INT);
                        $queryAction1->bindParam(2, $adminLevel, PDO::PARAM_INT);
                        $queryAction1->bindParam(3, $charUID[0], PDO::PARAM_INT);
                        $queryAction1->execute();
                        $queryAction = $conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID = ?");
                        $queryAction->bindParam(1, $charUID[0], PDO::PARAM_INT);
                        $queryAction->execute();
                        $logText = "UID: $charUID[0]; CID: $charUID[1]";
                        switch ($action) {
                            case 4:
                                $logAction = "GMA Function Added - Status 48";
                                break;
                            case 5:
                                $logAction = "GM PANEL FUNCTION Added";
                                break;
                            case 6:
                                $logAction = "GM STATUS REMOVED / THIS INCLUDE GM PANEL FUNCTION REMOVED ALSO!";
                                break;
                            case 7:
                                $logAction = "HIDE ACCOUNT FROM SEARCH LOGS";
                                break;
                            case 8:
                                $logAction = "GM Function Added - Status 32";
                                break;
                        }
                        break;
                }
                // Log action
                $queryLog = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, ?, ?, ?)");
                $queryLog->execute([$UserUID, $UserID, $logAction, $logText, $UserIP]);
                echo "$logAction";
            }
        }
    }
    // Kick the user
    $queryKick = $conn->prepare("EXEC [PS_GameDefs].[dbo].[Command] @serviceName = N'ps_game', @cmmd = N'/kickuid ?'");
    $queryKick->bindParam(1, $charUID[0], PDO::PARAM_INT);
    $queryKick->execute();
}
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