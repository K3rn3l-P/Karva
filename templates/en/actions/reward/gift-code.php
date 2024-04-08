<?php
session_start(); // Se non già avviata
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

try {
    // Verifica se l'utente è loggato
    if (!isset($_SESSION['UserUID'])) {
        throw new Exception("Unauthorized access");
    }

    // Verifica se è stato fornito il codice regalo
    if (!isset($_POST["code"])) {
        throw new Exception("Fill the Gift Code!");
    }

    /*
    if(isset($_POST['g-recaptcha-response'])){
        $captcha = $_POST['g-recaptcha-response'];
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $secretkey = "6Le-IkEaAAAAAJG_B7sAk5u10LkkLeQGFCKqjkIC";
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip"),true);
    if($response['success'] == false){
        throw new Exception("Spam verification failed, please try again!");
    }
    */

    $Code = htmlspecialchars($_POST["code"]);

    // Connessione al database utilizzando PDO
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Trova il codice regalo nel database
    $stmt = $conn->prepare("SELECT * FROM GiftCodes WHERE Code=:code AND Del=0 AND NOW() < EndDate");
    $stmt->bindParam(':code', $Code, PDO::PARAM_STR);
    $stmt->execute();
    $giftCode = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se il codice non esiste o è scaduto
    if (!$giftCode) {
        throw new Exception("Invalid Gift Code!");
    }

    $CodeID = $giftCode["ID"];
    $ItemID = $giftCode["ItemID"];
    $Count = $giftCode["Count"];
    $SP = $giftCode["SP"];
    $FEU = $giftCode["ForEachUser"];

    // Controlla se il codice è stato già utilizzato
    if ($FEU) {
        $stmt = $conn->prepare("SELECT 1 FROM GiftCodes_Log WHERE CodeID=:codeID AND UserUID=:userUID");
        $stmt->bindParam(':codeID', $CodeID, PDO::PARAM_INT);
        $stmt->bindParam(':userUID', $_SESSION['UserUID'], PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare("SELECT 1 FROM GiftCodes_Log WHERE CodeID=:codeID");
        $stmt->bindParam(':codeID', $CodeID, PDO::PARAM_INT);
    }
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        throw new Exception("This Gift Code has already been used!");
    }

    $Reward = "";

    // Se è previsto un premio di tipo oggetto
    if ($ItemID && $Count) {
        // Trova uno slot libero nel banco dell'utente
        $stmt = $conn->query("SELECT Slot FROM Users_Product WHERE UserUID={$_SESSION['UserUID']} ORDER BY Slot");
        $Slot = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row["Slot"] != $Slot) break;
            $Slot++;
        }

        // Se il banco dell'utente è pieno
        if ($Slot == 240) {
            throw new Exception("No free slots in your Bank Teller!");
        }

        // Inserisci l'oggetto nel banco dell'utente
        $stmt = $conn->prepare("INSERT INTO Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES (:userUID, :slot, :itemID, :count, 'GiftCode', NOW())");
        $stmt->bindParam(':userUID', $_SESSION['UserUID'], PDO::PARAM_INT);
        $stmt->bindParam(':slot', $Slot, PDO::PARAM_INT);
        $stmt->bindParam(':itemID', $ItemID, PDO::PARAM_INT);
        $stmt->bindParam(':count', $Count, PDO::PARAM_INT);
        $stmt->execute();

        // Registra l'utilizzo del codice regalo
        $stmt = $conn->prepare("INSERT INTO GiftCodes_Log (UserUID, CodeID) VALUES (:userUID, :codeID)");
        $stmt->bindParam(':userUID', $_SESSION['UserUID'], PDO::PARAM_INT);
        $stmt->bindParam(':codeID', $CodeID, PDO::PARAM_INT);
        $stmt->execute();

        // Ottieni il nome dell'oggetto
        $stmt = $conn->prepare("SELECT ItemName FROM Items WHERE ItemID=:itemID");
        $stmt->bindParam(':itemID', $ItemID, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        $Reward = $item['ItemName'] . " (x$Count)";
    }

    // Se è previsto un premio di tipo SP (punti)
    if ($SP) {
        // Aggiungi i punti SP all'utente
        $stmt = $conn->prepare("UPDATE Users_Master SET Point=Point+:sp WHERE UserUID=:userUID");
        $stmt->bindParam(':sp', $SP, PDO::PARAM_INT);
        $stmt->bindParam(':userUID', $_SESSION['UserUID'], PDO::PARAM_INT);
        $stmt->execute();
        $Reward .= $Reward ? " and $SP SP" : "$SP SP";
    }

    SetSuccessAlert("Congratulations! You got:  $Reward");
    header("location: $BackUrl");
    exit();
} catch (Exception $e) {
    // Gestisci le eccezioni e reindirizza a una pagina di errore
    $_SESSION['error_message'] = $e->getMessage();
    header("location: $BackUrl");
    exit();
}
?>
