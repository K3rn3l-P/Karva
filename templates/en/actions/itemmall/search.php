<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è un GM (Game Master)
    if (!$IsGM) {
        throw new Exception("Unauthorized access");
    }
    
    // Verifica se è stato fornito un nome dell'oggetto
    if (!isset($_GET['name'])) {
        throw new Exception("Item name not provided");
    }
    
    $itemName = $_GET['name'];
    
    // Prepara e esegui la query per ottenere l'ID dell'oggetto dal nome
    $queryItemID = $conn->prepare("SELECT ItemID FROM PS_GameDefs.dbo.items WHERE ItemName = ?");
    $queryItemID->bindValue(1, $itemName, PDO::PARAM_STR);
    $queryItemID->execute();
    
    // Ottieni l'ID dell'oggetto
    $ItemID = $queryItemID->fetch(PDO::FETCH_NUM);
    
    // Verifica se è stato trovato l'ID dell'oggetto
    if (!$ItemID) {
        throw new Exception("Item not found");
    }
    
    // Stampare l'ID dell'oggetto
    echo 'ItemID = '.$ItemID[0];
} catch (Exception $e) {
    // Gestisci l'eccezione e reindirizza a una pagina di errore
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>
