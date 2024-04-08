<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è uno staff
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Verifica se tutti i campi sono stati compilati correttamente
if (!isset($_GET["id"], $_GET["name"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Fill all fields");
    header("location: $BackUrl");
    return;
}

// Ottieni l'ID della gilda e il nome del personaggio
$GuildID = (int)$_GET["id"];
$CharName = GetClear($_GET["name"]);

// Trova la gilda
$stmt = $pdo->prepare("SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID = ? AND Del = 0");
$stmt->execute([$GuildID]);
$guild = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se la gilda esiste
if (!$guild) {
    SetErrorAlert("Guild not exists");
    header("location: $BackUrl");
    return;
}

// Trova il personaggio
$stmt = $pdo->prepare("SELECT UserID, CharID FROM PS_GameData.dbo.Chars WHERE CharName = ? AND Del = 0");
$stmt->execute([$CharName]);
$character = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se il personaggio esiste
if (!$character) {
    SetErrorAlert("Character not exists or deleted");
    header("location: $BackUrl");
    return;
}

$UserID = $character['UserID'];
$CharID = $character['CharID'];

// Verifica se il personaggio è già un membro di questa gilda
$stmt = $pdo->prepare("SELECT GuildLevel FROM PS_GameData.dbo.GuildChars WHERE GuildID = ? AND CharID = ? AND GuildLevel > 0 AND Del = 0");
$stmt->execute([$GuildID, $CharID]);
$guildCharacter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$guildCharacter) {
    SetErrorAlert("Character not exists in this guild");
    header("location: $BackUrl");
    return;
}

// Verifica se il personaggio è già il leader di questa gilda
if ($guildCharacter['GuildLevel'] === '1') {
    SetErrorAlert("Character already leader of this Guild");
    header("location: $BackUrl");
    return;
}

// Imposta il secondo grado per il vecchio leader
$stmt = $pdo->prepare("UPDATE PS_GameData.dbo.GuildChars SET GuildLevel = 2 WHERE GuildID = ? AND GuildLevel = 1 AND Del = 0");
$stmt->execute([$GuildID]);

// Imposta il primo grado per il nuovo leader
$stmt = $pdo->prepare("UPDATE PS_GameData.dbo.GuildChars SET GuildLevel = 1 WHERE GuildID = ? AND CharID = ?");
$stmt->execute([$GuildID, $CharID]);

// Aggiorna le informazioni della gilda
$stmt = $pdo->prepare("UPDATE PS_GameData.dbo.Guilds SET MasterUserID = ?, MasterCharID = ?, MasterName = ? WHERE GuildID = ?");
$stmt->execute([$UserID, $CharID, $CharName, $GuildID]);

SetSuccessAlert("GuildLeader changed to $CharName");
header("location: $BackUrl");
