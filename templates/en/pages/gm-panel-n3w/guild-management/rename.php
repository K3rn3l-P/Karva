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
$Name = GetClear($_GET["name"]);

// Find guild
$SqlRes = odbc_exec($odbcConn, "SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID={$GuildID} AND Del=0");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Guild not exists");
    header("location: $BackUrl");
    return;
}

// Name already exists
$SqlRes = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.Guilds WHERE GuildName='{$Name}' AND Del=0");
if (odbc_num_rows($SqlRes)) {
    SetErrorAlert("Guild with this name already exists.");
    header("location: $BackUrl");
    return;
}

// Update name
odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.Guilds SET GuildName='{$Name}' WHERE GuildID={$GuildID}");
			
SetSuccessAlert("GuildName changed to $Name");
header("location: $BackUrl");
