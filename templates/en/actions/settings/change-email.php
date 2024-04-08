<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è autenticato
    if (!$UserUID) {
        throw new Exception("Authentication required");
    }

    // Verifica se è stata fornita un'email e se non è vuota
    if (!isset($_POST["email"]) || empty($_POST["email"])) {
        throw new Exception("Fill in the email address");
    }

    // Controlla se l'email è valida e non supera la lunghezza massima
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    } 
    if (strlen($email) > 255) { // Limitiamo la lunghezza dell'email a 255 caratteri
        throw new Exception("Email address too long");
    }

    // Verifica se l'email è già in uso
    $stmt = $conn->prepare("SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE Email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        throw new Exception("Email already in use");
    }

    // Aggiorna l'email dell'utente nel database
    $stmt = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Email = :email WHERE UserUID = :userUID");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->execute();

    SetSuccessAlert("Email successfully changed");
    header("Location: $BackUrl");
} catch (Exception $e) {
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
}
?>
