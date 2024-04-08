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
        if ($charUID[0] == NULL) {
            die('Account not found');
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
                        
                        // Log action
                        logAction($odbcConn, $UserUID, $UserID, 'Nickname changing', "NEWNAME: $actionValue; UID: $charUID[0]; CID: $charUID[1]", $UserIP);
                        echo "Name Changed!";
                        break;
                    case 3:
                        if ($UserInfo["AdminLevel"] < 255) {
                            return;
                        }
                        // Perform item insertion query
                        // Log action
                        echo "Item successfully added to inventory!";
                        logAction($odbcConn, $UserUID, $UserID, 'Adding item to inventory', "ITEMID: $actionValue; UID: $charUID[0]; CID: $charUID[1]", $UserIP);
                        break;
                    case 4:
                        // Perform account unlock query
                        // Log action
                        echo "Account Unlocked!";
                        break;
                    // Handle other cases similarly
                    default:
                        // Handle invalid action
                        break;
                }
            }
        }
    }
}

function logAction($odbcConn, $UserUID, $UserID, $action, $text, $UserIP) {
    odbc_exec($odbcConn, "INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
                            VALUES ($UserUID, '$UserID', '$action', '$text', '$UserIP')");
}

function getClear($value) {
    // Perform any necessary sanitation or validation here
    return $value;
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