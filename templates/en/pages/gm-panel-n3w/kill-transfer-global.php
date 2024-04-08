<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit;
}

// Verifica se l'ID e il nome sono forniti e se l'ID è numerico
if (!isset($_GET["id"], $_GET["name"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Fill all fields");
    header("Location: $BackUrl");
    exit;
}

$charID = $_GET["id"];
$charName = GetClear($_GET["name"]);

// Trova il personaggio con le uccisioni
$stmt = $pdo->prepare("SELECT UserUID, K1 FROM PS_GameData.dbo.Chars WHERE CharID = :charID AND Del = 0");
$stmt->execute(array(':charID' => $charID));
$charData = $stmt->fetch(PDO::FETCH_ASSOC);

// Se il personaggio non esiste o è stato eliminato
if (!$charData) {
    SetErrorAlert("Character not exists or deleted");
    header("Location: $BackUrl");
    exit;
}

$UID = $charData["UserUID"];
$K1 = $charData["K1"];

// Trova il personaggio bersaglio
$stmt = $pdo->prepare("SELECT CharID FROM PS_GameData.dbo.Chars WHERE CharName = :charName AND Del = 0");
$stmt->execute(array(':charName' => $charName));
$targetData = $stmt->fetch(PDO::FETCH_ASSOC);

// Se il personaggio bersaglio non esiste o è stato eliminato
if (!$targetData) {
    SetErrorAlert("Character $charName not exists or deleted");
    header("Location: $BackUrl");
    exit;
}

$targetID = $targetData["CharID"];

// Aggiungi le uccisioni al nuovo personaggio
$stmt = $pdo->prepare("UPDATE PS_GameData.dbo.Chars SET K1 = K1 + :K1 WHERE CharID = :targetID");
$stmt->execute(array(':K1' => $K1, ':targetID' => $targetID));

// Rimuovi le uccisioni dal vecchio personaggio
$stmt = $pdo->prepare("UPDATE PS_GameData.dbo.Chars SET K1 = 0 WHERE CharID = :charID");
$stmt->execute(array(':charID' => $charID));

$_SESSION["suc"] = "$K1 kills transferred to $charName";
header("Location: $BackUrl");
exit;
