<?php
$command = $conn->prepare($query);
$command->bindParam(1, $val, PDO::PARAM_INT);
$command->execute();
$charId = $command->fetch(PDO::FETCH_NUM);
if ($charId == NULL) {
	echo "Character not found";
	return;
}
$slot = -1;
$command = $conn->prepare("SELECT MIN(Slots.Slot) AS OpenSlot FROM
(SELECT 0 AS Slot UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) AS Slots
LEFT JOIN
(SELECT c.Slot FROM PS_GameData.dbo.Chars AS c WHERE c.charName=? AND c.Del = 0) AS Chars ON Chars.Slot = Slots.Slot
WHERE Chars.Slot IS NULL");
$command->bindParam(1, $val, PDO::PARAM_INT);
$command->execute();
$slot = $command->fetch(PDO::FETCH_NUM);

if ($slot[0] > -1 && $slot[0] < 5) {
$queryRestore = $conn->prepare("UPDATE PS_GameData.dbo.Chars SET Del = 0, RemainTime = 0, Slot=".$slot[0].", Map=42, PosX=63 , PosZ=57, DeleteDate=NULL WHERE CharID = ?");
$queryRestore->bindParam(1, $charId[0], PDO::PARAM_INT);
$queryRestore->execute();

     echo "Successfully resurrected!";
    } 
	else {
	echo "No slots avaliable";
}