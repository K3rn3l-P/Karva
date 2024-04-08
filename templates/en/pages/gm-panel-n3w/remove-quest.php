<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit;
}

// Verifica se l'ID è fornito e se è numerico
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Wrong ID");
    header("Location: $BackUrl");
    exit;
}

$id = $_GET["id"];

// Trova l'elemento danneggiato
$stmt = $pdo->prepare("SELECT 1 FROM PS_GameData.dbo.CharQuests WHERE RowID = :id");
$stmt->execute(array(':id' => $id));
if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    SetErrorAlert("Row not exists");
    header("Location: $BackUrl");
    exit;
}

// Elimina l'elemento
$stmt = $pdo->prepare("DELETE FROM PS_GameData.dbo.CharQuests WHERE RowID = :id");
$stmt->execute(array(':id' => $id));

SetSuccessAlert("Quest successfully removed");
header("Location: $BackUrl");
exit;
