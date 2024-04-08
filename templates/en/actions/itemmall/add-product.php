<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è un GM
if (!$IsGM) {
    header("location: $BackUrl");
    return;
}

// Recupera i dati dalla richiesta POST
$icon = $_GET['icon'];
$category = $_POST['category'];
$name = $_POST['subject'];
$desc = $_POST['message'];
$price = $_POST['price'];

// Ottieni il codice del prodotto
$query_productcode = $conn->query("SELECT ISNULL(MAX(id), 0) FROM PS_WebSite.dbo.Products");
$product_code = $query_productcode->fetchColumn() + 1;
$code = 'PK_' . $product_code;

// Inserisci il nuovo prodotto nel database
$query_product = $conn->prepare("INSERT INTO PS_WebSite.dbo.Products (product_code, product_name, product_desc, product_img_name, price, product_indx) VALUES (?, ?, ?, ?, ?, ?)");
$query_product->execute([$code, $name, $desc, $icon, $price, $category]);

// Gestisci gli articoli associati al prodotto
$sel = 1;
while (isset($_POST[$sel]) && !empty($_POST[$sel])) {
    $itemID = $_POST[$sel];
    $count = $_POST["count$sel"];
    $enchant = $_POST["item$sel-enchant"];
    $gems = [];
    for ($i = 1; $i <= 6; $i++) {
        $gems[] = isset($_POST["item$sel-gem$i"]) ? $_POST["item$sel-gem$i"] : 0;
    }
    $improve = isset($_POST["item$sel-improve"]) ? $_POST["item$sel-improve"] : 0;

    // Inserisci l'articolo associato al prodotto nel database
    $query_product_buy = $conn->prepare("INSERT INTO PS_WebSite.dbo.Products_Buy (product_code, ItemID, ItemCount, Enchant, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, CanImprove) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query_product_buy->execute([$code, $itemID, $count, $enchant, ...$gems, $improve]);
    $sel++;
}

header("Location:$BackUrl");
?>
