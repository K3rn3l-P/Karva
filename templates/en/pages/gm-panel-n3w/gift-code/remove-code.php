<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit; // Termina lo script per evitare l'esecuzione del codice successivo
}

// Verifica la correttezza dell'ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Incorrect ID");
    header("Location: $BackUrl");
    exit;
}
$CodeID = (int) $_GET["id"];

// Verifica se il codice esiste nel database
$existing_code_query = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE ID = :id AND Del = 0");
$existing_code_query->execute(array(':id' => $CodeID));
if ($existing_code_query->rowCount() === 0) {
    SetErrorAlert("Code does not exist or has already been deleted");
    header("Location: $BackUrl");
    exit;
}

// Rimuove il codice impostando il flag Del a 1
$delete_code_query = $pdo->prepare("UPDATE PS_WebSite.dbo.GiftCodes SET Del = 1 WHERE ID = :id");
$delete_code_query->execute(array(':id' => $CodeID));

SetSuccessAlert("Code removed");
header("Location: $BackUrl");
exit;
?>
