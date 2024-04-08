<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Controllo dell'accesso
if (!$UserUID) {
    header("Location:$BackUrl");
    return;
}

// Validazione dei dati in ingresso
$productId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$productCount = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT);

if ($productId === null || $productCount === null || $productId === false || $productCount === false) {
    header("Location:$BackUrl");
    return;
}

// Controllo dei permessi
if (!is_numeric($UserUID) || $UserUID <= 0) {
    header("Location:$BackUrl");
    return;
}

// Trova il prodotto
$query = $conn->prepare("SELECT product_code, product_name, price FROM PS_WebSite.dbo.products WHERE id = ?");
$query->execute([$productId]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    SetErrorAlert("Product not exist");
    header("Location:$BackUrl");
    return;
}

// Calcola il costo totale
$cost = $product["price"] * $productCount;

// Verifica dei punti dell'utente
$query = $conn->prepare("SELECT Point FROM PS_Userdata.dbo.Users_Master WHERE UserUID = ?");
$query->execute([$UserUID]);
$point = $query->fetchColumn();

if ($point < $cost) {
    SetErrorAlert("Not enough point");
    header("Location:$BackUrl");
    return;
}

try {
    $conn->beginTransaction();

    // Aggiungi gli oggetti acquistati
    for ($i = 0; $i < $productCount; $i++) {
        $query = $conn->prepare("SELECT PI.*, I.*
                                FROM PS_WebSite.dbo.products_buy [PI]
                                LEFT JOIN PS_GameDefs.dbo.Items [I] ON [PI].ItemID = [I].ItemID
                                WHERE product_code = ?");
        $query->execute([$product["product_code"]]);
        $items = $query->fetchAll(PDO::FETCH_ASSOC);

        // Continua con la transazione se l'oggetto può essere migliorato
        foreach ($items as $item) {
            if ($item["CanImprove"]) {
                header("Location:/?p=itemmall&improve=$productId");
                return;
            }
        }

        // Aggiungi gli oggetti al banco o al magazzino
        foreach ($items as $item) {
            // Aggiorna l'incantesimo per l'armatura
            if (getItemType(floor($item["ItemID"] / 1000)) == "armor") {
                $item["Enchant"] += 50;
            }

            $query = $conn->prepare("INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate)
                                    VALUES (?, ?, ?, ?, 'Website ItemMall', GETDATE())");
            $query->execute([$UserUID, $slot, $item["ItemID"], $item["ItemCount"]]);

            $slot++;
            SetSuccessAlert("Item <b>{$item['ItemName']}</b> added to bank ($slot slot)");
        }

        // Rimuovi i punti
        $query = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point = Point - ? WHERE UserUID = ?");
        $query->execute([$product["price"], $UserUID]);

        // Log
        $query = $conn->prepare("INSERT INTO PS_GameData.dbo.PointLog (UserUID, CharID, UsePoint, ProductCode, UseDate, UseType, OrderNumber)
                                VALUES (?, 0, ?, ?, GETDATE(), '1', ?)");
        $query->execute([$UserUID, $product["price"], $productId, $productId]);
    }

    $conn->commit();

    // Redirect
    SetSuccessAlert("Product have been purchased");
    header("Location:$BackUrl");
} catch (PDOException $e) {
    // Annulla la transazione in caso di errore
    $conn->rollBack();
    // Gestisce l'errore
    SetErrorAlert("An error occurred while processing your request. Please try again later.");
    // Redirect
    header("Location:$BackUrl");
    return;
}
