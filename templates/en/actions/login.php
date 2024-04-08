<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php"); // Includi il file config.php per la connessione al database

// Assicurati che la connessione al database sia stata stabilita correttamente
if (!$conn) {
    // Logga un messaggio di errore e interrompi l'esecuzione dello script
    error_log("Connessione al database non riuscita in login.php", 0);
    die("Errore di connessione al database. Si prega di riprovare più tardi.");
}

// Se l'utente è già loggato, lo reindirizziamo
if (isset($_SESSION['UserUID']) && !empty($_SESSION['UserUID'])) {
    header("Location: $BackUrl");
    exit();
}

// Verifica la presenza dei dati POST
if (empty($_POST['username']) || empty($_POST['password'])) {
    $_SESSION['error'] = "Fill all fields";
    header("Location: $BackUrl");
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

if (strlen($username) < 3 || strlen($username) > 18 || strlen($password) < 3 || strlen($password) > 20) {
    $_SESSION['error'] = "Login and password must be 3-18 characters long";
    header("Location: $BackUrl");
    exit();
}

// Controllo del numero di tentativi
$query = $conn->prepare("SELECT COUNT(*) FROM TryLogin WHERE IP=:user_ip AND DT > DATEADD(MINUTE, -5, GETDATE())");
$query->bindParam(':user_ip', $user_ip, PDO::PARAM_STR);
$query->execute();
$attempts = $query->fetchColumn();
if ($attempts > 5) {
    $_SESSION['error'] = "Exceeded limit attempts. Try later.";
    header("Location: $BackUrl");
    exit();
}

// Aggiunge un tentativo di login
$query = $conn->prepare("INSERT INTO TryLogin (IP, Username, Password) VALUES (:user_ip, :username, :password)");
$query->bindParam(':user_ip', $user_ip, PDO::PARAM_STR);
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->bindParam(':password', $password, PDO::PARAM_STR);
$query->execute();

// Verifica login e password
$query = $conn->prepare("SELECT UserUID FROM Users_Master WHERE UserID=:username AND Pw=:password");
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->bindParam(':password', $password, PDO::PARAM_STR);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Wrong username or password";
    header("Location: $BackUrl");
    exit();
}

// Login riuscito, impostiamo le variabili di sessione
$_SESSION['UserUID'] = $user['UserUID'];
$_SESSION['session_id'] = createSession($user['UserUID']);
header("Location: $BackUrl");
exit();
?>
