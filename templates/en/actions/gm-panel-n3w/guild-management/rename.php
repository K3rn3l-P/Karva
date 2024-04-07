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
$query = $conn->prepare("SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID=? AND Del=0");
$query->bindValue(1, $GuildID, PDO::PARAM_INT);
$query->execute();
if (!$query->rowCount()) {
    SetErrorAlert("Guild not exists");
    header("location: $BackUrl");
    return;
}

// Name already exists
$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Guilds WHERE GuildName=? AND Del=0");
$query->bindValue(1, $Name, PDO::PARAM_INT);
$query->execute();
if ($query->rowCount()) {
    SetErrorAlert("Guild with this name already exists.");
    header("location: $BackUrl");
    return;
}

// Update name
$query = $conn->prepare("UPDATE PS_GameData.dbo.Guilds SET GuildName=? WHERE GuildID=?");
$query->bindValue(1, $Name, PDO::PARAM_INT);
$query->bindValue(2, $GuildID, PDO::PARAM_INT);
$query->execute();

SetSuccessAlert("GuildName changed to $Name");

// Log action
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP])
		VALUES ($UserUID, '$UserID', 'Change guild name', 'GUILDID: $GuildID; NAME: $Name', '$UserIP')");
$query->execute();
		
header("location: $BackUrl");
