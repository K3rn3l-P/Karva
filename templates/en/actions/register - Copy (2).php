<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica la presenza dei dati POST
$required_fields = array("username", "password", "password_confirm", "email");
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        SetErrorAlert("Please fill in all fields.");
        header("Location: $BackUrl");
        return;
    }
}

// Recupera i dati inviati tramite POST
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$password_confirm = trim($_POST['password_confirm']);
$email = trim($_POST['email']);

// Controlla la lunghezza dell'username
if (strlen($username) < 4 || strlen($username) > 20) {
    SetErrorAlert("Username must be between 4 and 20 characters long.");
    header("Location: $BackUrl");
    return;
}

// Controlla la lunghezza della password
if (strlen($password) < 5 || strlen($password) > 15) {
    SetErrorAlert("Password must be between 5 and 15 characters long.");
    header("Location: $BackUrl");
    return;
}

// Validazione delle credenziali utente
$stmt = $conn->prepare("SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE UserID=?");
$stmt->execute([$username]);
if ($stmt->rowCount()) {
    SetErrorAlert("This username is already in use.");
    header("Location:$BackUrl");
    return;
}

$stmt = $conn->prepare("SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE Email=?");
$stmt->execute([$email]);
if ($stmt->rowCount()) {
    SetErrorAlert("Email already in use.");
    header("Location:$BackUrl");
    return;
}

// Generazione del PIN segreto
$pin = strtoupper(getRandomString(7));

// Determina i valori booleani per le colonne di tipo bit
$admin = false; // Esempio: non è un amministratore
$useQueue = false; // Esempio: non usa la coda
$status = 0; // Esempio: stato predefinito
$leave = 0; // Esempio: nessun valore per il campo Leave
$userType = 'N'; // Esempio: tipo utente predefinito
$isNew = true; // Esempio: nuovo account

// Altre variabili
$adminLevel = 0; // Esempio: livello di amministrazione predefinito
$modiIp = NULL; // Esempio: nessun valore per ModiIp
$point = 0; // Esempio: nessun punto iniziale
$enpassword = NULL; // Esempio: nessuna password criptata
$birth = NULL; // Esempio: nessuna data di nascita
$shoutbox = NULL; // Esempio: nessun valore per Shoutbox
$skype = NULL; // Esempio: nessun account Skype
$mainCharID = NULL; // Esempio: nessun ID del personaggio principale
$sign = NULL; // Esempio: nessuna firma
$grade = NULL; // Esempio: nessun grado
$causaban = NULL; // Esempio: nessuna causa di ban
$veces = 0; // Esempio: nessun numero di volte
$cmds = 0; // Esempio: nessun comando
$chekeo = 0; // Esempio: nessun controllo
$votePoint = 0; // Esempio: nessun punto voto

// Inserimento del nuovo account nella tabella Users_Master
$stmt = $conn->prepare("INSERT INTO PS_UserData.dbo.Users_Master (UserID, Pw, JoinDate, Admin, AdminLevel, UseQueue, Status, Leave, LeaveDate, UserType, UserIp, ModiIp, ModiDate, Point, Enpassword, Birth, IsNew, Shoutbox, Email, Skype, MainCharID, Sign, Grade, causaban, veces, CMDS, chekeo, VotePoint) 
VALUES (?, ?, GETDATE(), ?, ?, ?, ?, ?, NULL, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Esegui la query di inserimento con i valori appropriati
$stmt->execute([$username, $password, $admin, $adminLevel, $useQueue, $status, $leave, $userType, $user_ip, $modiIp, $point, $enpassword, $birth, $isNew, $shoutbox, $email, $skype, $mainCharID, $sign, $grade, $causaban, $veces, $cmds, $chekeo, $votePoint]);


// Recupero dell'ID dell'utente appena inserito
$stmt = $conn->prepare("SELECT UserUID FROM PS_UserData.dbo.Users_Master WHERE UserID=?");
$stmt->execute([$username]);
$userUID = $stmt->fetchColumn();

// Inserimento del PIN segreto nel database
$stmt = $conn->prepare("INSERT INTO PS_UserData.dbo.Users_PIN (UserUID, PIN) VALUES (?, ?)");
$stmt->execute([$userUID, $pin]);

// Aggiornamento del progresso di referral
$stmt = $conn->prepare("INSERT INTO PS_WebSite.dbo.RefMoney (UserUID) VALUES (?)");
$stmt->execute([$userUID]);

// Se l'utente è stato registrato tramite referral, aggiorna anche la tabella Ref
if ($refer) {
    $stmt = $conn->prepare("INSERT INTO PS_WebSite.dbo.Ref (UserUID, RefUID) VALUES (?, ?)");
    $stmt->execute([$refer, $userUID]);
    SetSuccessAlert("You registered as a referral");
}

// Messaggio di successo e reindirizzamento
SetSuccessAlert("Account <b>$username</b> successfully registered.<br />Your secret PIN for password recovery: <b>$pin</b>");
header("location:$BackUrl");
?>
