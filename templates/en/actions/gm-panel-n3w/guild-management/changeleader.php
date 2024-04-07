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
$query = $conn->prepare("SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID=? AND Del=0");
$query->bindValue(1, $GuildID, PDO::PARAM_INT);
$query->execute();
if (!$query->rowCount()) {
    SetErrorAlert("Guild not exists");
    header("location: $BackUrl");
    return;
}

// Find character
$query = $conn->prepare("SELECT UserID, CharID, CharName FROM PS_GameData.dbo.Chars WHERE CharName=? AND Del=0");
$query->bindValue(1, $CharName, PDO::PARAM_INT);
$query->execute();
$character = $query->fetch(PDO::FETCH_ASSOC);						
// Not exists
if (!$character) {
    SetErrorAlert("Character not exists or deleted");
    header("location: $BackUrl");
    return;
}
$UserID = $character['UserID'];
$CharName = $character['CharName'];
$CharID = $character['CharID'];

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
$query = $conn->prepare("UPDATE PS_GameData.dbo.Guilds SET MasterUserID=?, MasterCharID=?, MasterName=? WHERE GuildID=?");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$query->bindValue(2, $CharID, PDO::PARAM_INT);
$query->bindValue(3, $CharName, PDO::PARAM_INT);
$query->bindValue(4, $GuildID, PDO::PARAM_INT);
$query->execute();
			
SetSuccessAlert("GuildLeader changed to $CharName");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Change guild leader', 'GUILDID: $GuildID; CHARNAME: $CharName', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");
