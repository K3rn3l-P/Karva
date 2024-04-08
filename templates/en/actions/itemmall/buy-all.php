<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autenticato
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}

// Verifica se la sessione dei prodotti esiste e non è vuota
if (!isset($_SESSION["products"]) || empty($_SESSION["products"])) {
	SetErrorAlert("Cart is empty!");
	header("Location:$BackUrl");
	return;
}

// Ciclo su ogni prodotto nel carrello
foreach ($_SESSION["products"] as $productId => $productCount) {
	// Trova il prodotto nel database
	$query = $conn->prepare("SELECT product_code, product_name, price FROM PS_WebSite.dbo.products WHERE id=?");
	$query->execute([$productId]);
	$product = $query->fetch(PDO::FETCH_ASSOC);

	if (!$product) {
		continue;
	}

	$cost = $product["price"] * $productCount;

	// Ottieni i punti dell'utente
	$query = $conn->prepare("SELECT Point FROM PS_Userdata.dbo.Users_Master WHERE UserUID=?");
	$query->execute([$UserUID]);
	$point = $query->fetchColumn();

	// Verifica se ci sono abbastanza punti
	if ($point < $cost) {
		SetErrorAlert("Not enough points");
		header("Location:$BackUrl");
		return;
	}

	// Ottieni gli oggetti del prodotto
	$queryItems = $conn->prepare("SELECT ItemID, ItemCount FROM PS_Website.dbo.products_buy WHERE product_code=?");
	$queryItems->execute([$product["product_code"]]);
	$items = $queryItems->fetchAll(PDO::FETCH_ASSOC);

	// Trova gli slot liberi
	$slots = [];
	$querySlots = odbc_exec($odbcConn, "SELECT Slot FROM PS_Billing.dbo.Users_Product WHERE UserUID=$UserUID");
	while ($slotData = odbc_fetch_array($querySlots)) {
		$slots[] = $slotData['Slot'];
	}

	$index = 0;
	foreach ($items as $item) {
		if ($index >= count($slots)) {
			SetErrorAlert("Can't buy $product[product_name]: bank is full");
			header("Location:$BackUrl");
			return;
		}

		$slot = $slots[$index++];
		$queryInsertItem = $conn->prepare("INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES (?, ?, ?, ?, ?, GETDATE())");
		$queryInsertItem->execute([$UserUID, $slot, $item["ItemID"], $item["ItemCount"], 'Website ItemMall']);
	}

	// Rimuovi i punti
	$queryUpdatePoints = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point=Point-? WHERE UserUID=?");
	$queryUpdatePoints->execute([$cost, $UserUID]);

	// Registra l'azione nell'oggetto PointLog
	$queryLog = $conn->prepare("INSERT INTO PS_GameData.dbo.PointLog (UserUID, CharID, UsePoint, ProductCode, UseDate, UseType, OrderNumber) VALUES (?, 0, ?, ?, GETDATE(), '1', ?)");
	$queryLog->execute([$UserUID, $cost, $productId, $productId]);
}

// Tutti gli articoli sono stati acquistati con successo
SetSuccessAlert("All items have been purchased");

// Svuota il carrello
unset($_SESSION["products"]);

// Reindirizza alla pagina precedente
header("Location:$BackUrl");
