<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è un GM (Game Master)
    if (!$IsGM) {
        throw new Exception("Unauthorized access");
    }
    
    // Verifica se è stato fornito un codice
    if (!isset($_GET['code'])) {
        throw new Exception("Product code not provided");
    }
    
    $code = $_GET['code'];
    
    // Prepara e esegui le query per eliminare il prodotto e i relativi acquisti
    $query_product = $conn->prepare("DELETE FROM PS_WebSite.dbo.products WHERE Product_code = ?");
    $query_product->bindValue(1, $code, PDO::PARAM_INT);
    $query_product->execute();
    
    $query_product_buy = $conn->prepare("DELETE FROM PS_WebSite.dbo.products_buy WHERE Product_code = ?");
    $query_product_buy->bindValue(1, $code, PDO::PARAM_INT);
    $query_product_buy->execute();
    
    // Reindirizza dopo l'eliminazione
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
} catch (Exception $e) {
    // Gestisci l'eccezione e reindirizza a una pagina di errore
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>
