<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
/*
$verificationFailed = 0;

    if(isset($_POST['g-recaptcha-response'])){
        $captcha = $_POST['g-recaptcha-response'];
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $secretkey = "6Le-IkEaAAAAAJG_B7sAk5u10LkkLeQGFCKqjkIC";

    
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip"),true);
    */
    // if($response['success'] == false){
    //     SetErrorAlert("Spam verification failed, please try again!");
    //     header("Location:$BackUrl");
	// 	return;
    // } 
    // else {

        // Verifica se l'utente è già autenticato, in tal caso reindirizzalo
        if (isset($_SESSION['UserUID']) && !empty($_SESSION['UserUID'])) {
            SetErrorAlert("You are already logged in.");
            header("Location: $BackUrl");
            exit();
        }
        
        // Verifica se sono stati inviati i dati del modulo
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validazione dell'input dell'utente
            $id = isset($_POST['userid']) ? trim($_POST['userid']) : '';
            $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        
            if (empty($id) || empty($answer)) {
                SetErrorAlert("User ID and answer are required.");
                header("Location: $BackUrl");
                exit();
            }
        
            // Query per recuperare il PIN dell'utente
            $queryAnswer = $conn->prepare('
                SELECT p.UserUID, p.PIN
                FROM PS_UserData.dbo.Users_PIN p
                JOIN PS_UserData.dbo.Users_Master m ON p.UserUID = m.UserUID
                WHERE m.UserID = ?');
            $queryAnswer->bindParam(1, $id, PDO::PARAM_STR);
            $queryAnswer->execute();
            $row = $queryAnswer->fetch(PDO::FETCH_ASSOC);
        
            if (!$row) {
                SetErrorAlert("Invalid user ID.");
                header("Location: $BackUrl");
                exit();
            }
        
            $uid = $row['UserUID'];
            $pwd = $row['PIN'];
        
            if ($pwd != $answer) {
                SetErrorAlert("Secret Key does not match.");
                header("Location: $BackUrl");
                exit();
            }
        
            // Query per recuperare la password criptata dell'utente
            $queryPassword = $conn->prepare('SELECT Pw FROM PS_UserData.dbo.Users_Master WHERE UserUID = ?');
            $queryPassword->bindParam(1, $uid, PDO::PARAM_STR);
            $queryPassword->execute();
            $encryptedPassword = $queryPassword->fetchColumn();
        
            if (!$encryptedPassword) {
                SetErrorAlert("Password not found.");
                header("Location: $BackUrl");
                exit();
            }
        
            // Imposta il messaggio di successo senza visualizzare la password
            SetSuccessAlert("<p class='text-center'>Once you submit your recovery request, you will be redirected to the 'My tickets' section where you can provide additional details about your password recovery request. Please make sure to select the 'Technical support' section when submitting your ticket for password reset. <a href='/?p=login'>Alternatively, you can join our Discord server for immediate assistance.</a></p>");
        }
        
        // Aggiungi il testo e il pulsante qui
        ?>
        <p>Once you submit your recovery request, you will be redirected to the "My tickets" section where you can provide additional details about your password recovery request. Please make sure to select the "Technical support" section when submitting your ticket for password reset. <a href='/?p=login'>Alternatively, you can join our Discord server for immediate assistance.</a></p>
        <a class="nice_button support-button" href="/?p=support">Go to Support</a>
        