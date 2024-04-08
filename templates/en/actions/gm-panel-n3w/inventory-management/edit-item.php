<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_POST["uid"], $_POST["typeid"]) || !is_numeric($_POST["uid"]) || !is_numeric($_POST["typeid"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit();
}

$ItemUID = $_POST["uid"];
$TypeID = $_POST["typeid"];
$gem1 = isset($_POST["gem-1"]) && is_numeric($_POST["gem-1"]) ? $_POST["gem-1"] : 0;
$gem2 = isset($_POST["gem-2"]) && is_numeric($_POST["gem-2"]) ? $_POST["gem-2"] : 0;
$gem3 = isset($_POST["gem-3"]) && is_numeric($_POST["gem-3"]) ? $_POST["gem-3"] : 0;
$gem4 = isset($_POST["gem-4"]) && is_numeric($_POST["gem-4"]) ? $_POST["gem-4"] : 0;
$gem5 = isset($_POST["gem-5"]) && is_numeric($_POST["gem-5"]) ? $_POST["gem-5"] : 0;
$gem6 = isset($_POST["gem-6"]) && is_numeric($_POST["gem-6"]) ? $_POST["gem-6"] : 0;

$str = isset($_POST["str"]) && is_numeric($_POST["str"]) ? $_POST["str"] : 0;
$dex = isset($_POST["dex"]) && is_numeric($_POST["dex"]) ? $_POST["dex"] : 0;
$rec = isset($_POST["rec"]) && is_numeric($_POST["rec"]) ? $_POST["rec"] : 0;
$int = isset($_POST["int"]) && is_numeric($_POST["int"]) ? $_POST["int"] : 0;
$wis = isset($_POST["wis"]) && is_numeric($_POST["wis"]) ? $_POST["wis"] : 0;
$luc = isset($_POST["luc"]) && is_numeric($_POST["luc"]) ? $_POST["luc"] : 0;
$hp = isset($_POST["hp"]) && is_numeric($_POST["hp"]) ? $_POST["hp"] : 0;
$sp = isset($_POST["sp"]) && is_numeric($_POST["sp"]) ? $_POST["sp"] : 0;
$mp = isset($_POST["mp"]) && is_numeric($_POST["mp"]) ? $_POST["mp"] : 0;
$enchant = isset($_POST["enchant"]) && is_numeric($_POST["enchant"]) ? $_POST["enchant"] : 0;

$craftname = sprintf("%02d%02d%02d%02d%02d%02d%02d%02d%02d%02d", $str, $dex, $rec, $int, $wis, $luc, $hp, $mp, $sp, $enchant);

$query = $conn->prepare("UPDATE PS_GameData.dbo.CharItems SET ItemID = Type * 1000 + ?, TypeID = ?, Gem1 = ?, Gem2 = ?, Gem3 = ?, Gem4 = ?, Gem5 = ?, Gem6 = ?, Craftname = ? WHERE ItemUID = ?");
$result1 = $query->execute([$TypeID, $TypeID, $gem1, $gem2, $gem3, $gem4, $gem5, $gem6, $craftname, $ItemUID]);

$query = $conn->prepare("UPDATE PS_GameData.dbo.UserStoredItems SET ItemID = Type * 1000 + ?, TypeID = ?, Gem1 = ?, Gem2 = ?, Gem3 = ?, Gem4 = ?, Gem5 = ?, Gem6 = ?, Craftname = ? WHERE ItemUID = ?");
$result2 = $query->execute([$TypeID, $TypeID, $gem1, $gem2, $gem3, $gem4, $gem5, $gem6, $craftname, $ItemUID]);

if ($result1 && $result2) {
    SetSuccessAlert("Item changed");
} else {
    SetErrorAlert("Item changing error");
}

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, '[InventoryManagement] Edit item', 'ITEMUID: $ItemUID', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
