<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit();
}

$id = $_GET["id"];

$query = $conn->prepare("SELECT * FROM PS_GameLog.dbo.ActionLog WHERE row = ?");
$query->execute([$id]);
$Item = $query->fetch(PDO::FETCH_ASSOC);

if (!$Item) {
    SetErrorAlert("ID does not exist");
    header("Location: $BackUrl");
    exit();
}

$Info = $Item["Text2"];
$ItemUID = $Item["Value1"];
$ItemID = $Item["Value2"];
$Type = floor($ItemID / 1000);
$TypeID = $ItemID % 1000;

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = ? AND LoginStatus = 1");
$query->execute([$Item["UserUID"]]);
if ($query->rowCount() > 0) {
    SetErrorAlert("User is currently in game. You must kick them first.");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.CharItems WHERE ItemUID = ? UNION ALL SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID = ? UNION ALL SELECT 1 FROM PS_GameData.dbo.MarketItems WHERE ItemUID = ?");
$query->execute([$ItemUID, $ItemUID, $ItemUID]);
if ($query->rowCount() > 0) {
    SetErrorAlert("Item has already been restored");
    header("Location: $BackUrl");
    exit();
}

$GemsStr = trim(substr($Info, 0, strpos($Info, "(")));
$Gems = explode(",", $GemsStr);
$Craftname = substr($Info, strpos($Info, ":") + 1, 20);

$Slot = 0;
while ($Slot <= 240) {
    $query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = ? AND Slot = ?");
    $query->execute([$Item["UserUID"], $Slot]);
    if ($query->rowCount() == 0) {
        break;
    }
    $Slot++;
}

if ($Slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID, UserUID, ItemID, ItemUID, Type, TypeID, Slot, Quality, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, Craftname, [Count], Maketime, Maketype, Del) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'X', 0)");
$query->execute([1, $Item["UserUID"], $ItemID, $ItemUID, $Type, $TypeID, $Slot, 0, $Gems[0], $Gems[1], $Gems[2], $Gems[3], $Gems[4], $Gems[5], $Craftname, 1]);

$Slot++;

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, ?, ?, ?)");
$query->execute([$UserUID, $UserID, 'Restore dropped item', "ROW: $id; USERUID: {$Item['UserUID']}; USERID: {$Item['UserID']}; ITEMUID: $ItemUID; ITEMID: $ItemID", $UserIP]);

SetSuccessAlert("Item successfully restored to slot {$Slot} of the warehouse");
header("Location: $BackUrl");
exit();
?>
