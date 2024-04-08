<?php
// error_handler.php

// Funzione per la gestione degli errori
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // Formatta il messaggio di errore
    $errorMessage = "Error: $errstr in $errfile on line $errline";

    // Registra l'errore in un file di log
    error_log($errorMessage, 3, "C:/Logs/error_log.txt");

    // Visualizza un messaggio di errore generico nel browser
    echo "Si è verificato un errore. Si prega di riprovare più tardi.";

    // Interrompe l'esecuzione dello script
    exit;
}

// Imposta la funzione di gestione degli errori personalizzata
set_error_handler("customErrorHandler");
?>
