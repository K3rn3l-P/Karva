<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è uno staff
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Verifica dell'ID corretto
if (!isset($_GET["uid"], $_GET["item"]) || !is_numeric($_GET["uid"]) || !is_numeric($_GET["item"])) {
    SetErrorAlert("Wrong ID");
    header("location: $BackUrl");
    return;
}

$UserUID = $_GET["uid"];
$ItemUID = $_GET["item"];

// Cerca l'oggetto
$stmt = $conn->prepare("SELECT * FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = :UserUID AND ItemUID = :ItemUID");
$stmt->bindParam(':UserUID', $UserUID, PDO::PARAM_INT);
$stmt->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
$stmt->execute();

// Controlla se l'oggetto esiste
if ($stmt->rowCount() === 0) {
    SetErrorAlert("Item not exists");
    header("location: $BackUrl");
    return;
}

$Item = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se l'utente è attualmente in gioco
$stmt = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.Chars WHERE UserUID = :UserUID AND LoginStatus = 1");
$stmt->bindParam(':UserUID', $UserUID, PDO::PARAM_INT);
$stmt->execute();

// Se l'utente è in gioco, mostra un avviso
if ($stmt->rowCount() > 0) {
    SetErrorAlert("User currently in game. You must kick him before of all.");
    header("location: $BackUrl");
    return;
}

// Rimuovi o decrementa l'oggetto
if ((int)$Item["Count"] <= 1) {
    $stmt = $conn->prepare("DELETE FROM PS_GameData.dbo.UserStoredItems WHERE UserUID = :UserUID AND ItemUID = :ItemUID");
} else {
    $stmt = $conn->prepare("UPDATE PS_GameData.dbo.UserStoredItems SET Count = Count - 1 WHERE UserUID = :UserUID AND ItemUID = :ItemUID");
}
$stmt->bindParam(':UserUID', $UserUID, PDO::PARAM_INT);
$stmt->bindParam(':ItemUID', $ItemUID, PDO::PARAM_INT);
$result = $stmt->execute();

// Mostra un messaggio di successo o di errore
if ($result) {
    SetSuccessAlert("One item removed");
} else {
    SetErrorAlert("Item removing error");
}

header("location: $BackUrl");
?>
