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

$query = $conn->prepare("SELECT * FROM PS_GameLog.dbo.BrokenItems WHERE ID = ?");
$query->execute([$id]);
$row = $query->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    SetErrorAlert("ID does not exist");
    header("Location: $BackUrl");
    exit();
}

if ($row["Res"]) {
    SetErrorAlert("Already restored");
    header("Location: $BackUrl");
    exit();
}

$Info = $row["Info"];
$ItemID = $row["ItemID"];
$Type = floor($ItemID / 1000);
$TypeID = $ItemID % 1000;

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = ? AND LoginStatus = 1");
$query->execute([$row["UserUID"]]);

if ($query->fetchColumn()) {
    SetErrorAlert("User currently in game. You must kick them out first.");
    header("Location: $BackUrl");
    exit();
}

$GemsStr = trim(substr($Info, 0, strpos($Info, "(")));
$Gems = explode(",", $GemsStr);
$Craftname = substr($Info, strpos($Info, ":") + 1, 20);

$Slot = 0;
while ($Slot <= 240) {
    $query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = ? AND Slot = ?");
    $query->execute([$row["UserUID"], $Slot]);

    if (!$query->fetchColumn()) {
        break;
    }
    $Slot++;
}

if ($Slot == 240) {
    SetErrorAlert("Warehouse is full");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("UPDATE PS_GameLog.dbo.BrokenItems SET Res = 1 WHERE ID = ?");
$query->execute([$id]);

$query = $conn->prepare("INSERT INTO PS_GameData.dbo.UserStoredItems (ServerID, UserUID, ItemID, ItemUID, Type, TypeID, Slot, Quality, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, Craftname, [Count], Maketime, Maketype, Del) VALUES (1, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, 'X', 0)");
$query->execute([$row["UserUID"], $ItemID, $row["ItemUID"], $Type, $TypeID, $Slot, $Gems[0], $Gems[1], $Gems[2], $Gems[3], $Gems[4], $Gems[5], $Craftname]);

$Slot++;
SetSuccessAlert("Item successfully restored to slot $Slot of the warehouse");

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, 'Restore broken item', 'ID: $id', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
