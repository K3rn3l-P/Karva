<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica che l'utente sia loggato
    if (!$UserUID) {
        throw new Exception("User not logged in");
    }

    // Verifica che l'id del prodotto sia stato fornito e sia numerico
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        throw new Exception("Invalid product ID");
    }

    // Verifica che esista una sessione di prodotti
    if (!isset($_SESSION["products"])) {
        throw new Exception("No product session found");
    }

    // Ottieni l'id del prodotto dalla richiesta GET
    $productId = $_GET["id"];

    // Rimuovi il prodotto dalla sessione
    unset($_SESSION["products"][$productId]);

    // Reindirizza all'URL di provenienza
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
} catch (Exception $e) {
    // Gestisci l'eccezione e reindirizza a una pagina di errore
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>
