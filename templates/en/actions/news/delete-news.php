<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è uno staff
    if (!$IsStaff) {
        throw new Exception("Unauthorized access");
    }

    // Verifica se è stato fornito il parametro 'r' tramite GET
    if (!isset($_GET['r']) || empty($_GET['r'])) {
        throw new Exception("Row parameter is missing");
    }

    // Ottieni il valore del parametro 'r' dal GET
    $row = $_GET['r'];

    // Prepara e esegui la query per impostare Del=1 per la riga specificata
    $query = $conn->prepare("UPDATE PS_WebSite.dbo.News$lang SET Del=1 WHERE Row = ?");
    $query->bindParam(1, $row, PDO::PARAM_INT);
    $query->execute();

    // Reindirizza alla pagina precedente dopo l'aggiornamento
    header("location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
} catch (Exception $e) {
    // Gestisci l'eccezione e reindirizza a una pagina di errore
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>
