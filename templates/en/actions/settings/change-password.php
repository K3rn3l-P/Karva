<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è autenticato
    if (!$UserUID) {
        throw new Exception("Authentication required");
    }

    // Verifica se sono stati forniti tutti i campi
    $requiredFields = array("old-password", "new-password", "password-confirm");
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Fill in all the fields");
        }
    }

    $oldPassword = GetClear($_POST['old-password']);
    $newPassword = GetClear($_POST['new-password']);
    $passwordConfirm = GetClear($_POST['password-confirm']);

    // Verifica la validità della nuova password
    if (strlen($newPassword) < 5 || strlen($newPassword) > 15) {
        throw new Exception("Password must be between 5 and 15 characters long");
    }
    if ($newPassword !== $passwordConfirm) {
        throw new Exception("Passwords do not match");
    }
    if ($UserID === $newPassword) {
        throw new Exception("Password must be different from login");
    }
    if (!preg_match('/^[a-zA-Z0-9]{5,15}+$/', $newPassword)) {
        throw new Exception("Password must contain only letters and numbers");
    }

    // Verifica che la vecchia password sia corretta
    $stmt = $conn->prepare("SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE UserUID = :userUID AND Pw = :oldPassword");
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':oldPassword', $oldPassword, PDO::PARAM_STR);
    $stmt->execute();
    if (!$stmt->fetchColumn()) {
        throw new Exception("Wrong password");
    }

    // Aggiorna la password dell'utente nel database
    $stmt = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Pw = :newPassword WHERE UserUID = :userUID AND Pw = :oldPassword");
    $stmt->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);
    $stmt->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
    $stmt->bindParam(':oldPassword', $oldPassword, PDO::PARAM_STR);
    $stmt->execute();

    SetSuccessAlert("Password successfully changed");
    header("Location: $BackUrl");
} catch (Exception $e) {
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
}
?>
