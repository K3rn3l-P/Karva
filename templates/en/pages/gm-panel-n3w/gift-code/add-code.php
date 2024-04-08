<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    exit; // Esce dallo script per evitare l'esecuzione del codice successivo
}

// Verifica se tutti i campi sono stati compilati nel modulo POST
$required_fields = array("code", "itemid", "count", "enddate");
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        SetErrorAlert("Fill all fields");
        header("Location: $BackUrl");
        exit;
    }
}

// Sanifica e ottiene i dati inviati dal modulo POST
$Code = GetClear($_POST['code']);
if (empty($Code)) {
    $Code = strtoupper(getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4) . '-' . getRandomString(4));
}
$ItemID = (int) GetClear($_POST['itemid']);
$Count = (int) GetClear($_POST['count']);
$SP = (int) GetClear($_POST['sp']);
$EndDate = GetClear($_POST['enddate']);
$FEU = isset($_POST["feu"]) ? 1 : 0;

// Verifica la correttezza dei valori numerici
if (!is_numeric($SP) || $SP > 5000 || $SP < 0) {
    SetErrorAlert("Incorrect SP");
    header("Location: $BackUrl");
    exit;
}
if (!is_numeric($Count) || $Count > 255 || $Count < 0) {
    SetErrorAlert("Incorrect Count");
    header("Location: $BackUrl");
    exit;
}
if (!is_numeric($ItemID) || $ItemID > 255255 || $ItemID < 0) {
    SetErrorAlert("Incorrect ItemID");
    header("Location: $BackUrl");
    exit;
}

// Verifica se il codice già esiste nel database
$existing_code_query = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE Code = :code AND Del = 0");
$existing_code_query->execute(array(':code' => $Code));
if ($existing_code_query->rowCount() > 0) {
    SetErrorAlert("This code already exists");
    header("Location: $BackUrl");
    exit;
}

// Aggiungi il codice al database
$insert_code_query = $pdo->prepare("INSERT INTO PS_WebSite.dbo.GiftCodes (Code, ForEachUser, EndDate, ItemID, Count, SP) VALUES (:code, :feu, :enddate, :itemid, :count, :sp)");
$insert_code_query->execute(array(':code' => $Code, ':feu' => $FEU, ':enddate' => $EndDate, ':itemid' => $ItemID, ':count' => $Count, ':sp' => $SP));

// Messaggio di successo e reindirizzamento
SetSuccessAlert("Code $Code added");
header("Location: $BackUrl");
exit;
?>
