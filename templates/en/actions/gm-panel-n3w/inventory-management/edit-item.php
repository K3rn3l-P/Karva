<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect ID
if (!isset($_POST["uid"], $_POST["typeid"]) || !is_numeric($_POST["uid"]) || !is_numeric($_POST["typeid"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
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

$craftname = "";
$craftname .= str_pad($str, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($dex, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($rec, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($int, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($wis, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($luc, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($hp, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($mp, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($sp, 2, '0', STR_PAD_LEFT);
$craftname .= str_pad($enchant, 2, '0', STR_PAD_LEFT);
			
$result = odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.CharItems SET ItemID=Type * 1000 + $TypeID, TypeID=$TypeID, Gem1=$gem1, Gem2=$gem2, Gem3=$gem3, Gem4=$gem4, Gem5=$gem5, Gem6=$gem6, Craftname='$craftname' WHERE ItemUID=$ItemUID");
$result = odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.UserStoredItems SET ItemID=Type * 1000 + $TypeID, TypeID=$TypeID, Gem1=$gem1, Gem2=$gem2, Gem3=$gem3, Gem4=$gem4, Gem5=$gem5, Gem6=$gem6, Craftname='$craftname' WHERE ItemUID=$ItemUID");
$result ? SetSuccessAlert("Item changed") : SetErrorAlert("Item changing error");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', '[InventoryManagement] Edit item', 'ITEMUID: $ItemUID', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");
