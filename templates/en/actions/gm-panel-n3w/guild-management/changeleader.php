<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_GET["id"], $_GET["name"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Fill all fields");
    header("Location: $BackUrl");
    exit();
}

$GuildID = $_GET["id"];
$CharName = GetClear($_GET["name"]);

$query = $conn->prepare("SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID = ? AND Del = 0");
$query->execute([$GuildID]);
$guild = $query->fetch(PDO::FETCH_ASSOC);

if (!$guild) {
    SetErrorAlert("Guild not exists");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("SELECT UserID, CharID FROM PS_GameData.dbo.Chars WHERE CharName = ? AND Del = 0");
$query->execute([$CharName]);
$character = $query->fetch(PDO::FETCH_ASSOC);

if (!$character) {
    SetErrorAlert("Character not exists or deleted");
    header("Location: $BackUrl");
    exit();
}

$UserID = $character['UserID'];
$CharID = $character['CharID'];

$query = $conn->prepare("SELECT GuildLevel FROM PS_GameData.dbo.GuildChars WHERE GuildID = ? AND CharID = ? AND GuildLevel > 0 AND Del = 0");
$query->execute([$GuildID, $CharID]);
$guildCharacter = $query->fetch(PDO::FETCH_ASSOC);

if (!$guildCharacter) {
    SetErrorAlert("Character not exists in this guild");
    header("Location: $BackUrl");
    exit();
}

if ($guildCharacter['GuildLevel'] === 1) {
    SetErrorAlert("Character already leader of this Guild");
    header("Location: $BackUrl");
    exit();
}

$conn->beginTransaction();

$query = $conn->prepare("UPDATE PS_GameData.dbo.GuildChars SET GuildLevel = 2 WHERE GuildID = ? AND GuildLevel = 1 AND Del = 0");
$query->execute([$GuildID]);

$query = $conn->prepare("UPDATE PS_GameData.dbo.GuildChars SET GuildLevel = 1 WHERE GuildID = ? AND CharID = ?");
$query->execute([$GuildID, $CharID]);

$query = $conn->prepare("UPDATE PS_GameData.dbo.Guilds SET MasterUserID = ?, MasterCharID = ?, MasterName = ? WHERE GuildID = ?");
$query->execute([$UserID, $CharID, $CharName, $GuildID]);

$conn->commit();

SetSuccessAlert("GuildLeader changed to $CharName");

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, 'Change guild leader', 'GUILDID: $GuildID; CHARNAME: $CharName', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
