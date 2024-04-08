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
$Name = GetClear($_GET["name"]);

$query = $conn->prepare("SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID = ? AND Del = 0");
$query->execute([$GuildID]);
$guild = $query->fetch(PDO::FETCH_ASSOC);

if (!$guild) {
    SetErrorAlert("Guild not exists");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Guilds WHERE GuildName = ? AND Del = 0");
$query->execute([$Name]);
if ($query->fetchColumn()) {
    SetErrorAlert("Guild with this name already exists.");
    header("Location: $BackUrl");
    exit();
}

$query = $conn->prepare("UPDATE PS_GameData.dbo.Guilds SET GuildName = ? WHERE GuildID = ?");
$query->execute([$Name, $GuildID]);

SetSuccessAlert("GuildName changed to $Name");

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, 'Change guild name', 'GUILDID: $GuildID; NAME: $Name', ?)");
$query->execute([$UserUID, $UserID, $UserIP]);

header("Location: $BackUrl");
exit();
?>
