<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è uno staff
    if (!$IsStaff) {
        throw new Exception("Unauthorized access");
    }

    // Verifica se sono stati forniti tutti i parametri necessari
    if (!isset($_POST['title'], $_POST['editor1'], $_POST['category'], $_POST['image'], $_POST['new'])) {
        throw new Exception("One or more parameters are missing");
    }

    // Ottieni i valori dei parametri dal POST
    $title = $_POST['title'];
    $text = $_POST['editor1'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $row = $_POST['new'];

    // Prepara e esegui la query per aggiornare il record nel database
    $query = $conn->prepare("UPDATE PS_WebSite.dbo.News$lang SET Title = ?, Text = ?, Category = ?, Image = ? WHERE Row = ?");
    $query->bindParam(1, $title, PDO::PARAM_STR);
    $query->bindParam(2, $text, PDO::PARAM_STR);
    $query->bindParam(3, $category, PDO::PARAM_STR);
    $query->bindParam(4, $image, PDO::PARAM_STR);
    $query->bindParam(5, $row, PDO::PARAM_INT); // Presumo che Row sia un intero
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
