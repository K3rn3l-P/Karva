<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autenticato
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}

// Controlla i valori della richiesta POST
if (!isset($_POST["id"], $_POST["count"]) || !is_numeric($_POST["id"]) || !is_numeric($_POST["count"])) {
	header("Location:$BackUrl");
	return;
}

$id = $_POST["id"];
$count = $_POST["count"];

// Verifica il conteggio
if ($count > 20 || $count < 1) {
	SetErrorAlert("The quantity should be between 1 and 20.");
	header("Location:$BackUrl");
	return;
}

// Inizializza o recupera la variabile di sessione per i prodotti
session_start();
if (!isset($_SESSION["products"])) {
	$_SESSION["products"] = [];
}

// Controlla se ci sono troppi prodotti nella sessione
if (count($_SESSION["products"]) > 20) {
	SetErrorAlert("Too many products!");
	header("Location:$BackUrl");
	return;
}

// Trova il prodotto nel database
$query = $conn->prepare("SELECT product_name, price FROM PS_WebSite.dbo.products WHERE id=?");
$query->execute([$id]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
	SetErrorAlert("Product does not exist.");
	header("Location:$BackUrl");
	return;
}

// Aggiunge il prodotto alla sessione
$_SESSION["products"][$id] = $count;

// Reindirizza all'URL precedente
header("Location:$BackUrl");
