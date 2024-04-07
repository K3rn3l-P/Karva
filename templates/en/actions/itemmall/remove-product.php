<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$IsGM) {
	header("location: $BackUrl");
	return;
}
    
$code = $_GET['code'];

$query_product = $conn->prepare("DELETE FROM PS_WebSite.dbo.products WHERE Product_code= ?");
$query_product->bindValue(1, $code, PDO::PARAM_INT);
$query_product->execute();

$query_product_buy = $conn->prepare("DELETE FROM PS_WebSite.dbo.products_buy WHERE Product_code= ?");
$query_product_buy->bindValue(1, $code, PDO::PARAM_INT);
$query_product_buy->execute();

header("Location:$BackUrl");
