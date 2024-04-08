<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit; // Termina lo script per evitare l'esecuzione del codice successivo
}

// Inizializzazione delle variabili
$username = isset($_POST["username"]) ? GetClear($_POST['username']) : "";
$charname = isset($_POST["charname"]) ? GetClear($_POST['charname']) : "";
$forAll = isset($_POST["feu"]) ? 1 : 0;
$count = isset($_POST['count']) ? (int)$_POST['count'] : 0;
$itemid = isset($_POST['itemid']) ? (int)$_POST['itemid'] : 0;

// Verifica che tutti i campi necessari siano stati compilati correttamente
if ((!$username && !$charname && !$forAll) || !$count || $count > 255 || $itemid < 1001 || $itemid > 255255) {
    SetErrorAlert("Invalid input");
    header("Location: $BackUrl");
    exit;
}

// Recupera il nome dell'oggetto dall'ID fornito
$itemNameQuery = $pdo->prepare("SELECT ItemName FROM PS_GameDefs.dbo.Items WHERE ItemID = :itemid");
$itemNameQuery->execute(array(':itemid' => $itemid));
$item = $itemNameQuery->fetch(PDO::FETCH_ASSOC);
if (!$item) {
    $label = number_format($itemid, 0, '.', ' ');
    SetErrorAlert("Item $label does not exist");
    header("Location: $BackUrl");
    exit;
}
$itemName = $item['ItemName'];

// Includi il file appropriato in base alla scelta dell'utente
if ($username) {
    include_once("by-username.php");
} elseif ($charname) {
    include_once("by-charname.php");
} elseif ($forAll) {
    include_once("for-all.php");
}

header("Location: $BackUrl");
exit;
?>
