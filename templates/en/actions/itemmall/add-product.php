<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$IsGM) {
	header("location: $BackUrl");
	return;
}

$icon = $_GET['icon'];
$cate = $_POST['category'];
$name = $_POST['subject'];
$desc = $_POST['message'];
$price = $_POST['price'];


$query_productcode = $conn->prepare("SELECT ISNULL(MAX(id),0) FROM PS_WebSite.dbo.Products");
$query_productcode->execute();
$product_code = $query_productcode->fetch(PDO::FETCH_NUM);

$code = $product_code[0] + 1;
$code = 'PK_' . $code;

$query_product = $conn->prepare("INSERT INTO PS_WebSite.dbo.products (product_code, product_name, product_desc, product_img_name, price, product_indx) VALUES (?,?,?,?,?,?)");
$query_product->bindValue(1, $code, PDO::PARAM_INT);
$query_product->bindValue(2, $name, PDO::PARAM_INT);
$query_product->bindValue(3, $desc, PDO::PARAM_INT);
$query_product->bindValue(4, $icon, PDO::PARAM_INT);
$query_product->bindValue(5, $price, PDO::PARAM_INT);
$query_product->bindValue(6, $cate, PDO::PARAM_INT);
$query_product->execute();

$sel = 1;
while (isset($_POST[$sel]) && !empty($_POST[$sel])) {
    $itemID = $_POST[$sel];
    $count = $_POST["count$sel"];
    $enchant = $_POST["item$sel-enchant"];
    $gem1 = $_POST["item$sel-gem1"];
    $gem2 = $_POST["item$sel-gem2"];
    $gem3 = $_POST["item$sel-gem3"];
    $gem4 = $_POST["item$sel-gem4"];
    $gem5 = $_POST["item$sel-gem5"];
    $gem6 = $_POST["item$sel-gem6"];
    $improve = isset($_POST["item$sel-improve"]);
	
    $query_product_buy = $conn->prepare("INSERT INTO PS_WebSite.dbo.products_buy (product_code, ItemID, ItemCount, Enchant, Gem1, Gem2, Gem3, Gem4, Gem5, Gem6, CanImprove) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $query_product_buy->bindValue(1, $code, PDO::PARAM_INT);
    $query_product_buy->bindValue(2, $itemID, PDO::PARAM_INT);
    $query_product_buy->bindValue(3, $count, PDO::PARAM_INT);
    $query_product_buy->bindValue(4, $enchant, PDO::PARAM_INT);
    $query_product_buy->bindValue(5, $gem1, PDO::PARAM_INT);
    $query_product_buy->bindValue(6, $gem2, PDO::PARAM_INT);
    $query_product_buy->bindValue(7, $gem3, PDO::PARAM_INT);
    $query_product_buy->bindValue(8, $gem4, PDO::PARAM_INT);
    $query_product_buy->bindValue(9, $gem5, PDO::PARAM_INT);
    $query_product_buy->bindValue(10, $gem6, PDO::PARAM_INT);
    $query_product_buy->bindValue(11, $improve, PDO::PARAM_INT);
    $query_product_buy->execute();
    $sel++;
}

header("Location:$BackUrl");
