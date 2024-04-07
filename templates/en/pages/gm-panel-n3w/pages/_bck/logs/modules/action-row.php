<?php
if ($row["AdminLevel"] && $UserInfo["AdminLevel"] != 255)
	return;

$actionNameOriginal = array_key_exists($action, $ActionTypes) ? $ActionTypes[$action] : "Unknown action $row[ActionType]";
$actionName = $actionNameOriginal;
$info1 = "1:$row[Value1]  2:$row[Value2] 3:$row[Value3] 4:$row[Value4], 5:$row[Value5], 6:$row[Value6], 7:$row[Value7], 8:$row[Value8] 9:$row[Value9] 10:$row[Value10]";
$info2 = "1:$row[Text1] 2:$row[Text2] 3:$row[Text3] 4:$row[Text4]";
$text1 = $row["Text1"];
$text2 = $row["Text2"];
$text3 = $row["Text3"];
$text4 = $row["Text4"];

switch ($action) {
	case 106:
		$info1 = "";
		if ($row["Value1"]) {
			$name = $row["Text1"];
			$info1 = "Ressurected by <a href='/?p=gm-panel-n3w&sp=user-search&CharID=$row[Value1]'>$name</a>";
		} elseif ($row["Text2"] == "sel_base") {
			$info1 = "Basic ressurection";
		} elseif ($row["Text2"] == "timeover") {
			$info1 = "Basic ressurection (time over)";
		}
		break;
	case 107: // Entering
		$info1 = "IP: $text1";
		$info2 = "$text2 $text3 $text4";
		break;
	case 108: // Leaving
		$info1 = "";
		$info2 = "$text2 $text3 $text4";
		break;
	case 111: // Item received
		$actionName = "<span class='lightgreen' title='$actionNameOriginal'>Item received</span>";
		$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value3])</b> ItemID: <b>$row[Value2]</b></p>";
		if ($row["Text2"] == "ItemCreate") {
			$actionName = "<span class='pink' title='$actionNameOriginal'>Item created</span>";
			$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value3])</b> ItemID: <b>$row[Value2]</b></p>";
		}
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='MakeTime: $text4'>$label</p>";
		break;
	case 112: // Item losed
		$actionName = "<span class='indianred' title='$actionNameOriginal'>Item losed</span>";
		$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value3])</b> ItemID: <b>$row[Value2]</b></p>";
		if ($row["Text2"] == "use_item") {
			$actionName = "<span class='orange' title='$actionNameOriginal'>Item used</span>";
			$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value3])</b> ItemID: <b>$row[Value2]</b></p>";
		}
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='$text4'>$label</p>";
		break;
	case 113: // Item buy (npc/itemmall/billing)
		$actionName = "<span class='lightgreen' title='$actionNameOriginal'>Item purchased (NPC)</span>";
		$cost = $row["Value5"] < 0 ? 4294967295 + $row["Value5"] : $row["Value5"]; // Cast to uint
		$cost = number_format($row["Value5"], 0, '.', ' ');
		$money = $row["Value6"] < 0 ? 4294967295 + $row["Value6"] : $row["Value6"]; // Cast to uint
		$money = number_format($money, 0, '.', ' ');
		$info1 = "<p title='$row[Value1]'>
						<b class='item-name'>$text1 (x$row[Value4])</b> 
						ItemID: <b>$row[Value2]</b>
						<br />
						Cost: <b class='yellow-gray'>$cost</b>. Player gold: <b class='yellow-gray'>$money</b>
					</p>";
		$info2 = "Npc Store";
		if ($row["Text2"] == "PointItem") {
			$actionName = "<span class='pink' title='$actionNameOriginal'>Item purchased (Shop)</span>";
			$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value4])</b> ItemID: <b>$row[Value2]</b></p>";
			$info2 = "Item mall";
		} elseif ($row["Text2"] == "Billing") {
			$actionName = "<span class='orange' title='$actionNameOriginal'>Item taken (Bank)</span>";
			$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value4])</b> ItemID: <b>$row[Value2]</b></p>";
			$info2 = "Billing";
		}
		break;
	case 114: // Item sell to NPC
		$cost = $row["Value5"] < 0 ? 4294967295 + $row["Value5"] : $row["Value5"]; // Cast to uint
		$cost = number_format($row["Value5"], 0, '.', ' ');
		$money = $row["Value6"] < 0 ? 4294967295 + $row["Value6"] : $row["Value6"]; // Cast to uint
		$money = number_format($money, 0, '.', ' ');
		$info1 = "<p title='$row[Value1] $text4'>
					<span class='red'>Item selled</span>: 
					<b class='item-name'>$text1 (x$row[Value4])</b> 
					ItemID: <b>$row[Value2]</b>
					<br />
					Cost: <b class='yellow-gray'>$cost</b>. Player gold: <b class='yellow-gray'>$money</b>
				</p>";
		$label = getCraftname($row["Text2"]);
		$info2 = "<p title='MakeTime: $text3'>$label</p>";
		break;
	case 115: // Item got by Trade/Market
		$name = $row["Text2"];
		$actionName = "<span class='lightgreen' title='$actionNameOriginal'>Got item from <a href='/?p=gm-panel-n3w&sp=user-search&CharID=$row[Value3]'>$name</a></span>";
		$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value4])</b> ItemID: <b>$row[Value2]</b></p>";
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='MakeTime: $text4'>$label</p>";
		break;
	case 116: // Item given by Trade/Market
		$name = $row["Text2"];
		$actionName = "<span class='indianred' title='$actionNameOriginal'>Item given to <a href='/?p=gm-panel-n3w&sp=user-search&CharID=$row[Value3]'>$name</a></span>";
		$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value4])</b> ItemID: <b>$row[Value2]</b></p>";
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='From $text4'>$label</p>";
		break;
	case 118: // Item given by Duel window
		break;
	case 119: // Linking
		$success = $row["Value7"];
		$info1 = "<p title='$row[Value1]'>
					Linking <b class='lightgreen' title='$row[Value3]'>$text2</b> to <b class='item-name'>$text1</b> ItemID: <b>$row[Value2]</b>
				</p>";
		$info1 .= $row["Value7"] ? "<b class='green'>[SUCCESS]</b>" : "<b class='red'>[FAIL]</b>";
		// Info1 -> Linking cost
		$cost = $row["Value6"] < 0 ? 4294967295 + $row["Value6"] : $row["Value6"]; // Cast to uint
		$cost = number_format($cost, 0, '.', ' ');
		$info1 .= "<br />Linking cost: <b class='yellow-gray'>$cost</b>";
		// Info 2
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='MakeTime $text4'>$label</p>";
		break;
	case 121: // Put to warehouse
		$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value3])</b> ItemID: <b>$row[Value2]</b></p>";
		$info1 .= "From bag=$row[Value4], slot=$row[Value5]";
		// Info 2
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='MakeTime $text4'>$label</p>";
		break;
	case 122: // Take from warehouse
		$tax = number_format($row["Value8"], 0, '.', ' ');
		$info1 = "<p title='$row[Value1]'><b class='item-name'>$text1 (x$row[Value3])</b> ItemID: <b>$row[Value2]</b></p>";
		$info1 .= "From slot=$row[Value4] to bag=$row[Value6], slot=$row[Value7]";
		$info1 .= "<br />Tax: $tax";
		// Info 2
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='MakeTime $text4'>$label</p>";
		break;
	case 123: // Duel lose
		$name = $row["Text1"];
		$actionName = "<span class='lightred' title='$actionNameOriginal'><b>Duel losed</b></span> with <b>$name</b>";
		$info1 = "<p class='lightgreen'>Winner: <a href='/?p=gm-panel-n3w&sp=user-search&CharID=$row[Value1]'>$name</a></p>";
		$info2 = "";
		break;
	case 124: // Duel won
		$name = $row["Text1"];
		$actionName = "<span class='lightgreen' title='$actionNameOriginal'><b>Duel won</b></span> with <b>$name</b>";
		$info1 = "<p class='indianred'>Loser: <a href='/?p=gm-panel&sp=user-search&CharID=$row[Value1]'>$name</a></p>";
		$info2 = "";
		break;
	case 131: // Quest taken
		$info1 = "<p class=''>
					Quest <b class='pink'>$text1</b> taken from <span class='lightgreen'>$text2</span>
				</p>";
		$info2 = "QuestID: $row[Value1]<br />Value2: $row[Value2]";
		break;
	case 133: // Quest finished
		$info1 = "<p class=''>
					Quest <b class='pink'>$text1</b> finished
				</p>";
		$info2 = "QuestID: $row[Value1]<br />Value2: $row[Value2] Value3: $row[Value3] Value4: $row[Value4] Value5: $row[Value5]";
		break;
	case 141: // Skill learned
		$info1 = "<p class=''>
					Skill <b class='pink'>$text1</b> learned
				</p>";
		$info2 = "SkillID: $row[Value1] Level: $row[Value2]<br />Skill Point: $row[Value3]";
		break;
	case 146: // Level up
		$info1 = "";
		$info2 = "";
		break;
	case 151: // Statpoint distribution
		$info1 = "<p class=''>
					<b class='red'>[STR]</b>
					New value: <b class='pink'>$row[Value1]</b>
					<br />Old value: <b class='yellow-gray'>$row[Value2]</b>
				</p>";
		$info2 = "";
		break;
	case 152:
		$info1 = "<p class=''>
					<b class='yellow'>[DEX]</b>
					New value: <b class='pink'>$row[Value1]</b>
					<br />Old value: <b class='yellow-gray'>$row[Value2]</b>
				</p>";
		$info2 = "";
		break;
	case 153:
		$info1 = "<p class=''>
					<b class='dark-blue'>[INT]</b>
					New value: <b class='pink'>$row[Value1]</b>
					<br />Old value: <b class='yellow-gray'>$row[Value2]</b>
				</p>";
		$info2 = "";
		break;
	case 154:
		$info1 = "<p class=''>
					<b class='green'>[WIS]</b>
					New value: <b class='pink'>$row[Value1]</b>
					<br />Old value: <b class='yellow-gray'>$row[Value2]</b>
				</p>";
		$info2 = "";
		break;
	case 155:
		$info1 = "<p class=''>
					<b class='violet'>[REC]</b>
					New value: <b class='pink'>$row[Value1]</b>
					<br />Old value: <b class='yellow-gray'>$row[Value2]</b>
				</p>";
		$info2 = "";
		break;
	case 156:
		$info1 = "<p class=''>
					<b class='blue'>[LUC]</b>
					New value: <b class='pink'>$row[Value1]</b>
					<br />Old value: <b class='yellow-gray'>$row[Value2]</b>
				</p>";
		$info2 = "";
		break;
	case 163: // Money transfer
		$name = $row["Text1"];
		$money = $row["Value1"] < 0 ? 4294967295 + $row["Value1"] : $row["Value1"]; // Cast to uint
		$money = number_format($money, 0, '.', ' ');
		$oldMoney = $row["Value2"] < 0 ? 4294967295 + $row["Value1"] : $row["Value1"]; // Cast to uint
		$oldMoney = number_format($oldMoney, 0, '.', ' ');
		
		$info1 = "<b class='yellow-gray'>$money</b> gold transfered to <a href='/?p=gm-panel-n3w&sp=user-search&CharID=$row[Value3]'>$name</a>";
		$info2 = "Old money: <b class='yellow-gray'>$oldMoney</b>";
		break;
	case 164: // Teleport
		$name = $row["Text1"];
		
		$cost = $row["Value1"] < 0 ? 4294967295 + $row["Value1"] : $row["Value1"]; // Cast to uint
		$cost = number_format($cost, 0, '.', ' ');
		$money = $row["Value2"] < 0 ? 4294967295 + $row["Value2"] : $row["Value2"]; // Cast to uint
		$money = number_format($money, 0, '.', ' ');
		
		if (substr($name, 0, 5) == "(NPC)") {
			$actionName = "<span title='$actionNameOriginal'>Used GateKeeper</span>";
			$name = substr($name, 5);
			$info1 = "Used GateKeeper <b class='lightgreen'>$name</b>
						<br />NpcID: $row[Value3]";
			$info2 = "Cost: <b class='yellow-gray'>$cost</b>
					<br /> Money: <b class='yellow-gray'>$money</b>";
		} else {
			$actionName = "<span title='$actionNameOriginal'>Teleport to character?</span>";
			$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharID=$row[Value3]'>$name</a>";
			$info2 = "Value1: <b class='yellow-gray'>$cost</b>
					<br /> Money: <b class='yellow-gray'>$money</b>";
		}
		break;
	case 173: // Boss actions
		$bossName = $row["Text1"];
		$type = $row["Text2"];
		$charName = $row["Text3"];
		$bossId = $row["Value3"];
		// Mob hp
		$mobHp = castToUInt($row["Value1"]);
		$mobHp = number_format($mobHp, 0, '.', ' ');
		
		switch ($type) {
			case "damage":
				// Damage
				$damage = castToUInt($row["Value2"]);
				$damage = number_format($damage, 0, '.', ' ');
				$actionName = "<span title='$actionNameOriginal'>Player attacked boss</span>";
				$info1 = "Player <b class='orange'>$charName</b> attacked <b class='lightgreen'>$bossName</b> ($bossId)
						<br />Skill: $row[Value4]";
				$info2 = "Damage: <span class='red'>$damage</span> 
					<br />MobHP: <span class='orange'>$mobHp</span>";
				break;
			case "attack":
				// Damage
				$damage = castToUInt($row["Value2"]);
				$damage = number_format($damage, 0, '.', ' ');
				// Labels
				$actionName = "<span title='$actionNameOriginal'>Boss attacked player</span>";
				$info1 = "<b class='lightgreen'>$bossName</b> ($bossId) attacked player <b class='orange'>$charName</b>";
				$info2 = "Damage: <span class='red'>$damage</span> 
					<br />MobHP: <span class='orange'>$mobHp</span>";
				break;
			case "death":
				$actionName = "<span title='$actionNameOriginal'>Boss killed</span>";
				$info1 = "<b class='lightgreen'>$bossName</b> ($bossId) killed by player <b class='orange'>$charName</b>";
				$info2 = "Value1: $row[Value1] 
					<br />Value2: $row[Value2]";
				break;
			case "itemdrop":
				$actionName = "<span title='$actionNameOriginal'>Boss dropped item</span>";
				$info1 = "Boss: <b class='lightgreen'>$bossName</b> ($bossId) dropped item <b class='yellow-gray'>$text4</b>
						<br />to player <b class='orange'>$charName </b>";
				$info2 = "";
				break;
			case "moneydrop":
				// Money
				$money = castToUInt($row["Value2"]);
				$money = number_format($money, 0, '.', ' ');
				// Labels
				$actionName = "<span title='$actionNameOriginal'>Boss dropped money</span>";
				$info1 = "Boss: <b class='lightgreen'>$bossName</b> ($bossId) dropped <b class='yellow-gray'>$money</b> gold";
				$info2 = "";
				break;
			case "debuffadd":
				$actionName = "<span title='$actionNameOriginal'>Boss got debuff from player</span>";
				$info1 = "Player <b class='orange'>$charName</b> apply debuff to <b class='lightgreen'>$bossName</b> ($bossId)
						<br />Skill: $row[Value4]";
				$info2 = "SkillLv: <span class='green'>$row[Value2]</span> 
					<br />MobHP: <span class='orange'>$mobHp</span>";
				break;
			case "debuffremove":
				$actionName = "<span title='$actionNameOriginal'>Boss dispelled from debuff</span>";
				$info1 = "Boss <b class='lightgreen'>$bossName</b> ($bossId) dispelled from debuff <b class='indianred'>$row[Value4]</b>";
				$info2 = "SkillLv: <span class='green'>$row[Value2]</span> 
					<br />MobHP: <span class='orange'>$mobHp</span>";
				break;
			case "heal":
				$actionName = "<span title='$actionNameOriginal'>Boss healed</span>";
				$info1 = "Boss <b class='lightgreen'>$bossName</b> ($bossId) healed HP";
				$info2 = "Value2: <span class='green'>$row[Value2]</span>
					<br />MobHP: <span class='orange'>$mobHp</span>";
				break;
		}		
		break;
	case 180: // Admin actions
		$type = $row["Text1"];
		$info1 = "Text2: $text2; Text3: $text3; Text4: $text4";
		$info2 = "";
		
		switch ($type) {
			case "ItemCreate":
				$type = "<b class='indianred'>$type</b>";
				$info1 = $text3;
				break;
			case "SetStatus":
				$type = "<b class='red'>$type</b>";
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>
						<br /><b class='indianred'>$text3</b>";
				break;
			case "CountryCall":
				$type = "<b class='indianred'>$type</b>";
				break;
			case "CharKick":
				$type = "<b class='red'>$type</b>";
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>";
				break;
			case "NoticeAll":
				$type = "<b class='yellow'>$type</b>";
				$info1 = "Message: <b class='blue'>$text3</b>";
				break;
			case "MobCreate":
				$type = "<b class='dark-blue'>$type</b>";
				$info1 = "$text3";
				break;
			case "PickMobRemove":
			case "MobRemove":
				$info1 = "$text3";
				break;
			case "NpcCreate":
				$type = "<b class='pink'>$type</b>";
				$info1 = "$text3";
				break;
			case "NpcRemove":
				$type = "<b class='lightred'>$type</b>";
				$info1 = "$text3";
				break;
			case "MoveZone":
				$type = "<b class='lightgreen'>$type</b>";
				$info1 = "$text3";
				break;
			case "MoveCharZone":
			case "MoveTo":
				$type = "<b class='lightgreen'>$type</b>";
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>
						<br />$text3";
				break;
			case "MoveChar":
				$type = "<b class='lightgreen'>ASummon</b>";
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>
						<br />$text3";
				break;
			case "FindChar":
			case "CharInfo":
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>";
				break;
			case "EqClear":
			case "BagClear":
				$type = "<b class='dark-blue'>$type</b>";
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>";
				break;
			case "Cure":
				$info1 = "Target: <a href='/?p=gm-panel-n3w&sp=user-search&CharName=$text2'>$text2</a>";
				break;
			case "AttackEnable":
			case "AttackDisable":
			case "VisibleOFF":
			case "VisibleOn":
			case "MoveMap":
				$info1 = "";
				$info2 = "";
				break;
		}
		$actionName = "<span title='$actionNameOriginal'>
						<b class='violet'>[ADMIN]</b> -> $type
					</span>";
		break;	
	case 210: // Party enter
	case 211: // Party leave
		$info1 = "PartyID: $row[Value1]";
		$info2 = "";
		break;
	case 212: // Enchant
		$success = $row["Value7"];
		$info1 = "<p title='$row[Value1]'>
					Enchanting <b class='item-name'>$text1</b> (<b>$row[Value2]</b>) by <b class='lightgreen' title='$row[Value4]'>$text2</b> (<b>$row[Value3]</b>)
				</p>";
		$info1 .= $row["Value7"] ? "<b class='green'>[SUCCESS]</b>" : "<b class='red'>[FAIL]</b>";
		// Info1 -> Linking cost
		$cost = castToUInt($row["Value6"]);
		$cost = number_format($cost, 0, '.', ' ');
		$info1 .= "<br />Enchant cost: <b class='yellow-gray'>$cost</b>";
		// Info 2
		$label = getCraftname($row["Text3"]);
		$info2 = "<p title='MakeTime $text4'>$label</p>";
		break;
	case 213: // Reroll
		$info1 = "<p title='$row[Value1]'>
					Reroll <b class='item-name'>$text1</b> (<b>$row[Value2]</b>) by <b>$row[Value3]</b>
				</p>";
		$label1 = getOrange($row["Text2"]);
		$label2 = getOrange($row["Text3"]);
		$info2 = "<p>Old values - $label1</p> <p>New values - $label2</p>";
		break;
}
$time = strtotime($row["ActionTime"]);
?>
<tr>
	<td class="date-column sorting_asc" title="<?= date("Y-m-d H:i", $time) ?>">
		<?= date("d M H:i", $time) ?>
		<p>
			<span class='yellow-gray' style='font-size: 9px; color: gray; cursor: pointer; margin: 0 2px;' onclick="setStartDate('<?= date("Y-m-d\TH:i", $time) ?>')">to start</span>
			<span class='yellow-gray' style='font-size: 9px; color: gray; cursor: pointer; margin: 0 2px;' onclick="setEndDate('<?= date("Y-m-d\TH:i", $time) ?>')">to end</span>
		</p>
	</td>
	<td class="userid-column">
		<a href='/?p=gm-panel-n3w&sp=user-search&UserUID=<?= $row["UserUID"] ?>'><?= $row["UserID"] ?></a>
		<?php if ($row["UserUID"]) : ?>
			<p class='yellow-gray' style='font-size: 10px; color: gray; cursor: pointer;' onclick="showByUserId('<?= $row["UserID"] ?>')">Show logs<p>
		<?php endif ?>
	</td>
	<td class="charname-column">
		<a href='/?p=gm-panel-n3w&sp=user-search&CharID=<?= $row["CharID"] ?>'><?= $row["CharName"] ?></a>
		<?php if ($row["CharName"]) : ?>
			<p class='yellow-gray' style='font-size: 10px; color: gray; cursor: pointer;' onclick="showByCharname('<?= $row["CharName"] ?>')">Show logs<p>
		<?php endif ?>
	</td>
	<td class="level-column"><?= $row["CharLevel"] ?></td>
	<td class="map-column"><?= getMapName($row["MapID"]) ?></td>
	<td class="action-column">
		<?= $actionName ?>
		<p class='yellow-gray' style='font-size: 9px; color: gray; cursor: pointer; margin: 0 2px;' onclick="selectActionType(<?= $row["ActionType"] ?>)">Select action type</p>
	</td>
	<td class="info1-column"><?= $info1 ?></td>
	<td class="info2-column"><?= $info2 ?></td>
</tr>