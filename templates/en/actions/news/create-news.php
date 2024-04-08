<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è uno staff
    if (!$IsStaff) {
        throw new Exception("Unauthorized access");
    }
    
    // Verifica se tutti i campi sono stati inviati
    $requiredFields = ['title', 'editor1', 'image', 'category'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Please fill in all fields");
        }
    }

    // Ottieni i dati inviati dal modulo
    $title = $_POST['title'];
    $text = $_POST['editor1'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    // Prepara e esegui la query per inserire la notizia nel database
    $query = $conn->prepare("INSERT INTO PS_WebSite.dbo.News$lang (Title, Text, Date, Image, Category, Author) VALUES (?, ?, GETDATE(), ?, ?, ?)");
    $query->bindParam(1, $title, PDO::PARAM_STR);
    $query->bindParam(2, $text, PDO::PARAM_STR);
    $query->bindParam(3, $image, PDO::PARAM_STR);
    $query->bindParam(4, $category, PDO::PARAM_STR);
    $query->bindParam(5, $UserID, PDO::PARAM_INT);
    $query->execute();

    // Reindirizza alla pagina precedente dopo l'inserimento della notizia
    header("location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
} catch (Exception $e) {
    // Gestisci l'eccezione e reindirizza a una pagina di errore
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>
