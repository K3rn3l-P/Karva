<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect ID | Name not exists
if (!isset($_GET["id"], $_GET["name"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Fill all fields");
    header("location: $BackUrl");
    return;
}

$GuildID = $_GET["id"];
$CharName = GetClear($_GET["name"]);

// Find guild
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID={$GuildID} AND Del=0");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Guild not exists");
    header("location: $BackUrl");
    return;
}

// Find character
$SqlRes = odbc_exec($odbcConn, "SELECT UserID, CharID, CharName FROM PS_GameData.dbo.Chars WHERE CharName='{$CharName}' AND Del=0");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Character not exists or deleted");
    header("location: $BackUrl");
    return;
}
$UserID = odbc_result($SqlRes, "UserID");
$CharName = odbc_result($SqlRes, "CharName");
$CharID = odbc_result($SqlRes, "CharID");

// Find guild character
$SqlRes = odbc_exec($odbcConn, "SELECT GuildLevel FROM PS_GameData.dbo.GuildChars WHERE GuildID=$GuildID AND CharID=$CharID AND GuildLevel>0 AND Del=0");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Character not exists in this guild");
    header("location: $BackUrl");
    return;
}
// Character already leader of this Guild
if (odbc_result($SqlRes, "GuildLevel") === 1) {
    SetErrorAlert("Character already leader of this Guild");
    header("location: $BackUrl");
    return;
}

// Set 2 rank for old leader
odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.GuildChars SET GuildLevel=2 WHERE GuildID=$GuildID AND GuildLevel=1 AND Del=0");
// Set 1 rank for new leader
odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.GuildChars SET GuildLevel=1 WHERE GuildID=$GuildID AND CharID=$CharID");
// Update guild info
odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.Guilds SET MasterUserID='{$UserID}',MasterCharID=$CharID,MasterName='{$CharName}' WHERE GuildID=$GuildID");
			
SetSuccessAlert("GuildLeader changed to $CharName");
header("location: $BackUrl");
