<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autenticato
if (!$UserUID) {
    SetErrorAlert("You must be logged");
    header("Location: $BackUrl");
    return;
}

// Verifica la presenza e la correttezza dei valori della richiesta POST
if (!isset($_POST["poll"], $_POST["variant"]) || !is_numeric($_POST["poll"]) || !is_numeric($_POST["variant"])) {
    header("Location: $BackUrl");
    return;
}

$poll = $_POST["poll"];
$variant = $_POST["variant"];

// Verifica se l'utente ha già votato
$stmt = $conn->prepare("SELECT 1 FROM PS_WebSite.dbo.PollLog WHERE PollID = ? AND (UserUID = ? OR UserIP = ?)");
$stmt->execute([$poll, $UserUID, $UserIP]);
if ($stmt->fetchColumn()) {
    header("Location: $BackUrl");
    return;
}

// Verifica se la variante esiste
$stmt = $conn->prepare("SELECT 1 FROM PS_WebSite.dbo.PollVariants WHERE PollID = ? AND ID = ?");
$stmt->execute([$poll, $variant]);
if (!$stmt->fetchColumn()) {
    header("Location: $BackUrl");
    return;
}

// Inserisce il voto dell'utente nel registro dei voti
$stmt = $conn->prepare("INSERT INTO PS_WebSite.dbo.PollLog (UserUID, PollID, VariantID, UserIP) VALUES (?, ?, ?, ?)");
$stmt->execute([$UserUID, $poll, $variant, $UserIP]);

// Aggiorna i voti per la variante selezionata
$stmt = $conn->prepare("UPDATE PS_WebSite.dbo.PollVariants SET Votes = Votes + 1 WHERE ID = ?");
$stmt->execute([$variant]);

header("Location: $BackUrl");
?>
