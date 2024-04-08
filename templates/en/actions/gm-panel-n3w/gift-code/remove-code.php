<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è uno staff
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Verifica l'id corretto
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Incorrect ID");
    header("location: $BackUrl");
    return;
}
$CodeID = $_GET["id"];

// Seleziona il codice
$query = $conn->prepare("SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE ID = ? AND Del = 0");
$query->execute([$CodeID]);
$code = $query->fetch(PDO::FETCH_ASSOC);

// Verifica se il codice esiste
if (!$code) {
    SetErrorAlert("Code does not exist or has already been deleted");
    header("location: $BackUrl");
    return;
}

// Rimuovi il codice
$query = $conn->prepare("UPDATE PS_WebSite.dbo.GiftCodes SET Del = 1 WHERE ID = ?");
$query->execute([$CodeID]);

SetSuccessAlert("Code removed");

// Registra l'azione nel log
$query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP]) VALUES (?, ?, 'Remove giftcode', ?, ?)");
$query->execute([$UserUID, $UserID, "ID: $CodeID", $UserIP]);

header("location: $BackUrl");
?>
