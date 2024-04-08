<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php"); // Includi il file config.php per la connessione al database

// Funzione per eseguire la ricerca degli utenti
function searchUsers($query, $val) {
    global $conn;
    $queryUserID = $conn->prepare($query);
    $queryUserID->bindParam(1, $val, PDO::PARAM_INT);
    $queryUserID->execute();
    while($userDetails = $queryUserID->fetch(PDO::FETCH_NUM)) {
        include 'modules/user.php';				
    }
}

// Funzione per eseguire il logging delle azioni
function logAction($actionType, $value) {
    global $conn, $UserUID, $UserID, $UserIP;
    $logQuery = $conn->prepare('INSERT INTO AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, ?, ?, ?)');
    $logQuery->execute([$UserUID, $UserID, $actionType, $value, $UserIP]);
}

// Processa i parametri di ricerca dell'utente
if (isset($_GET["UserID"]) && !empty($_GET["UserID"])) {
    $val = $_GET["UserID"];
    $query = "SELECT * FROM Users_Master WHERE UserID=?";
    logAction('[User-Search] By UserID', "VALUE: $val");
    searchUsers($query, $val);
} elseif (isset($_GET["UserUID"]) && !empty($_GET["UserUID"])) {
    $val = $_GET["UserUID"];
    $query = "SELECT * FROM Users_Master WHERE UserUID=?";
    logAction('[User-Search] By UserUID', "VALUE: $val");
    searchUsers($query, $val);
} elseif (isset($_GET["CharID"]) && !empty($_GET["CharID"])) {
    $val = $_GET["CharID"];
    $query = "SELECT * FROM Users_Master WHERE UserUID=(SELECT TOP 1 UserUID FROM Chars WHERE CharID=?)";
    logAction('[User-Search] By CharID', "VALUE: $val");
    searchUsers($query, $val);
} elseif (isset($_GET["CharName"]) && !empty($_GET["CharName"])) {
    $val = $_GET["CharName"];
    $query = "SELECT * FROM Users_Master WHERE UserUID=(SELECT TOP 1 UserUID FROM Chars WHERE CharName=? ORDER BY Del)";
    logAction('[User-Search] By CharName', "VALUE: $val");
    searchUsers($query, $val);
} elseif (isset($_GET["UserIP"]) && !empty($_GET["UserIP"])) {
    $val = $_GET["UserIP"];
    $query = "SELECT * FROM Users_Master WHERE UserUID IN (SELECT DISTINCT UserUID FROM ActionLog WHERE text1=? AND actiontype=107)";
    logAction('[User-Search] By IP', "VALUE: $val");
    searchUsers($query, $val);
}
?>
