<?php
// Include il file di configurazione
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica la presenza dei dati POST
$required_fields = array("username", "password", "password_confirm", "email");
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        SetErrorAlert("Please fill in all fields.");
        header("Location: $BackUrl");
        exit;
    }
}

// Recupera i dati inviati tramite POST e puliscili
$username = clean_input($_POST['username']);
$email = clean_input($_POST['email']);
$password = clean_input($_POST['password']);
$password_confirm = clean_input($_POST['password_confirm']);

// Funzione per pulire l'input dagli attacchi XSS e SQL injection
function clean_input($data) {
    // Rimuove spazi iniziali e finali
    $data = trim($data);
    // Rimuove i caratteri non sicuri per SQL
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Controlla la lunghezza dell'username
if (strlen($username) < 4 || strlen($username) > 18) {
    SetErrorAlert("Username must be between 4 and 18 characters long.");
    header("Location: $BackUrl");
    exit;
}

// Controlla la lunghezza della password
if (strlen($password) < 8 || strlen($password) > 20) {
    SetErrorAlert("Password must be between 8 and 20 characters long.");
    header("Location: $BackUrl");
    exit;
}

// Password comuni nelle lingue più utilizzate
$common_passwords = array(
    // Inglese
    "password", "123456", "qwerty", "12345678", "123456789", 
    "12345", "1234567", "password1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertyuiop", "abc123", "qwerty123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "welcome", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "password1", "123123", "password", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "password1", "welcome", "admin123", "qwerty123",

    // Spagnolo
    "contraseña", "123456", "qwerty", "12345678", "123456789", 
    "12345", "1234567", "contraseña1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertyuiop", "abc123", "qwerty123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "welcome", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "contraseña1", "123123", "contraseña", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "contraseña1", "welcome", "admin123", "qwerty123",

    // Francese
    "motdepasse", "123456", "azerty", "12345678", "123456789", 
    "12345", "1234567", "motdepasse1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "azertyuiop", "abc123", "azerty123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "bienvenue", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "motdepasse1", "123123", "motdepasse", 
    "123456", "12345678", "123456789", "azerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "motdepasse1", "bienvenue", "admin123", "azerty123",

    // Cinese (semplificato)
    "密码", "123456", "密码", "12345678", "123456789", 
    "12345", "1234567", "密码1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qazwsxedc", "abc123", "password123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "welcome", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "password1", "123123", "password", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "password1", "welcome", "admin123", "qwerty123",

    // Hindi
    "पासवर्ड", "123456", "qwerty", "12345678", "123456789", 
    "12345", "1234567", "पासवर्ड1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertyuiop", "abc123", "qwerty123", "monkey", "letmein", 
    "ड्रैगन", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "स्वागत", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "पासवर्ड1", "123123", "पासवर्ड", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "पासवर्ड1", "स्वागत", "admin123", "qwerty123",

    // Tedesco
    "passwort", "123456", "qwertz", "12345678", "123456789", 
    "12345", "1234567", "passwort1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertzuiop", "abc123", "qwertz123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "willkommen", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "passwort1", "123123", "passwort", 
    "123456", "12345678", "123456789", "qwertz", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "passwort1", "willkommen", "admin123", "qwertz123",

    // Italiano
    "password", "123456", "qwerty", "12345678", "123456789", 
    "12345", "1234567", "password1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertyuiop", "abc123", "qwerty123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "benvenuto", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "password1", "123123", "password", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "password1", "benvenuto", "admin123", "qwerty123",

    // Russo
    "пароль", "123456", "qwerty", "12345678", "123456789", 
    "12345", "1234567", "password1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertyuiop", "abc123", "qwerty123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "добро", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "password1", "123123", "пароль", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "password1", "добро", "admin123", "qwerty123",

    // Filippino
    "password", "123456", "qwerty", "12345678", "123456789", 
    "12345", "1234567", "password1", "123123", "admin", 
    "1234", "1234567890", "1qaz2wsx", "1qazxsw2", "123qwe", 
    "qwertyuiop", "abc123", "qwerty123", "monkey", "letmein", 
    "dragon", "111111", "baseball", "iloveyou", "trustno1", 
    "1234567", "sunshine", "master", "123123", "welcome", 
    "shadow", "ashley", "football", "jesus", "michael", 
    "ninja", "mustang", "password1", "123123", "password", 
    "123456", "12345678", "123456789", "qwerty", "admin", 
    "1234567890", "love", "secret", "letmein", "login", 
    "password1", "welcome", "admin123", "qwerty123"
);

if (in_array(strtolower($password), $common_passwords)) {
    SetErrorAlert("Common passwords are not allowed. Please choose a stronger password.");
    header("Location: $BackUrl");
    exit;
}


// Verifica la presenza dell'indirizzo email
if (!isset($email) || empty($email)) {
    SetErrorAlert("Please provide an email address.");
    header("Location: $BackUrl");
    exit;
}

// Validazione dell'indirizzo email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    SetErrorAlert("Invalid email address.");
    header("Location: $BackUrl");
    exit;
}

// Regola per le email consentite e più sicure
function validateEmail($email) {
    // Regex per le email
    $email_regex = '/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+(?:[a-zA-Z]{2,})$/';

    // Array di domini email sicuri
    $safe_email_domains = array(
    // Gmail
    "gmail.com", "googlemail.com",
    // Yahoo
    "yahoo.com", "yahoo.co.uk", "yahoo.co.in", "rocketmail.com", "y7mail.com",
    // Outlook / Hotmail / MSN
    "outlook.com", "hotmail.com", "live.com", "msn.com", "windowslive.com",
    // AOL
    "aol.com", "aim.com",
    // iCloud / Apple
    "icloud.com", "me.com", "mac.com",
    // ProtonMail
    "protonmail.com",
    // Tutanota
    "tutanota.com",
    // Zoho
    "zoho.com",
    // Yandex
    "yandex.com", "yandex.ru",
    // Mail.com
    "mail.com",
    // GMX
    "gmx.com",
    // FastMail
    "fastmail.com",
    // Disroot
    "disroot.org",
    // Runbox
    "runbox.com",
    // Posteo
    "posteo.net",
    // StartMail
    "startmail.com",
    // Hushmail
    "hushmail.com",
    // LuxSci
    "luxsci.net",
    // CounterMail
    "countermail.com",
    // Neomailbox
    "neomailbox.com",
    // Tuffmail
    "tuffmail.net",
    // Soverin
    "soverin.net",
    // Kolab Now
    "kolabnow.com",
    // Mailfence
    "mailfence.com",
    // SCRYPTmail
    "scryptmail.com",
    // Criptext
    "criptext.com",
    // VFEmail
    "vfemail.net",
    // StartTLS
    "starttls.email",
    // CanalePlus
    "canal-plus.net",
    // Elude
    "elude.in",
    // Anonaddy
    "anonaddy.com",
    // Secure Email
    "secure-email.org",
    // MsgSafe.io
    "msgsafe.io",
    // TuttiSafe
    "tuttisafe.com",
    // SafeInbox
    "safeinbox.net",
    // SecureMail
    "securemail.io",
    // SecurelySend
    "securelysend.com",
    // SecureBox
    "securebox.com",
    // SecureDrop
    "securedrop.com"
    // Aggiungi altri domini email sicuri se necessario
);

    // Estrai il dominio dall'email fornita
    $email_domain = substr(strrchr($email, "@"), 1);

    // Verifica se l'email corrisponde alla regex e se il dominio è nella lista dei domini sicuri
    if (preg_match($email_regex, $email) && in_array($email_domain, $safe_email_domains)) {
        return true;
    } else {
        return false;
    }
}

// Verifica se l'email fornita è valida e sicura
if (!validateEmail($email)) {
    SetErrorAlert("Please provide a valid and secure email address.");
    header("Location: $BackUrl");
    exit;
}

// Verifica se le password coincidono
if ($password !== $password_confirm) {
    SetErrorAlert("Passwords do not match.");
    header("Location: $BackUrl");
    exit;
}

// Validazione più rigorosa della password
function validatePassword($password) {
    // Verifica se la password contiene almeno una lettera maiuscola
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    // Verifica se la password contiene almeno una lettera minuscola
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    
    // Verifica se la password contiene almeno un numero
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    // Verifica se la password contiene almeno un carattere speciale
    if (!preg_match('/[$@$!%*#?&]/', $password)) {
        return false;
    }
    
    // Verifica se la password ha una lunghezza compresa tra 8 e 20 caratteri
    if (strlen($password) < 8 || strlen($password) > 20) {
        return false;
    }
    
    return true;
}

// Utilizzo della funzione validatePassword per controllare se la password è valida
if (!validatePassword($password)) {
    SetErrorAlert("Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be between 8 and 20 characters long.");
    header("Location: $BackUrl");
    exit;
}

// Verifica unicità dell'username
$stmt = $conn->prepare("SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE UserID=?");
$stmt->execute([$username]);
if ($stmt->rowCount()) {
    SetErrorAlert("This username is already in use.");
    header("Location:$BackUrl");
    exit;
}

// Verifica unicità dell'email
$stmt = $conn->prepare("SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE Email=?");
$stmt->execute([$email]);
if ($stmt->rowCount()) {
    SetErrorAlert("Email already in use.");
    header("Location:$BackUrl");
    exit;
}

// Generazione del PIN segreto
$pin = strtoupper(getRandomString(7));

// Determina i valori predefiniti per le colonne del database
$admin = false; // Esempio: non è un amministratore
$useQueue = false; // Esempio: non usa la coda
$status = 0; // Esempio: stato predefinito
$leave = 0; // Esempio: nessun valore per il campo Leave
$userType = 'N'; // Esempio: tipo utente predefinito
$isNew = true; // Esempio: nuovo account
$adminLevel = 0; // Esempio: livello di amministrazione predefinito
$modiIp = NULL; // Esempio: nessun valore per ModiIp
$point = 0; // Esempio: nessun punto iniziale
$enpassword = substr(password_hash($password, PASSWORD_DEFAULT), 0, 31); // Hash della password
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
SetSuccessAlert("Account <b>$username</b> successfully registered.<br />Your secret PIN for password recovery: <b>$pin</b><br />Please make sure to keep this PIN safe, as your main password has been encrypted.");
header("location:$BackUrl");

?>
