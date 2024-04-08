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

// Ottieni l'ID della gilda e il nome
$GuildID = (int)$_GET["id"];
$Name = GetClear($_GET["name"]);

// Trova la gilda con l'ID specificato
$stmt = $pdo->prepare("SELECT * FROM PS_GameData.dbo.Guilds WHERE GuildID = ? AND Del = 0");
$stmt->execute([$GuildID]);
$guild = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se la gilda esiste
if (!$guild) {
    SetErrorAlert("Guild not exists");
    header("location: $BackUrl");
    return;
}

// Verifica se esiste già una gilda con lo stesso nome
$stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.Guilds WHERE GuildName = ? AND Del = 0");
$stmt->execute([$Name]);
if ($stmt->fetchColumn()) {
    SetErrorAlert("Guild with this name already exists.");
    header("location: $BackUrl");
    return;
}

// Aggiorna il nome della gilda
$stmt = $pdo->prepare("UPDATE PS_GameData.dbo.Guilds SET GuildName = ? WHERE GuildID = ?");
$stmt->execute([$Name, $GuildID]);

SetSuccessAlert("GuildName changed to $Name");
header("location: $BackUrl");
