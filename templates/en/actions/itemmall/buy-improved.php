<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autenticato
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}

// Verifica i valori inviati
if (!isset($_POST["product"]) || !is_numeric($_POST["product"])) {
	header("Location:$BackUrl");
	return;
}

$productId = $_POST["product"];
$improvements = isset($_POST["item"]) && is_array($_POST["item"]) ? $_POST["item"] : array();

// Trova il prodotto
$query = $conn->prepare("SELECT product_code, product_name, price FROM PS_WebSite.dbo.products WHERE id=?");
$query->execute([$productId]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
	SetErrorAlert("Product not exist");
	header("Location:$BackUrl");
	return;
}

$cost = $product["price"];
$totalCost = $cost;

// Ottieni i gemme del server
$queryGems = $conn->prepare("SELECT * FROM PS_GameDefs.dbo.Items WHERE Type=30");
$queryGems->execute();
$gems = $queryGems->fetchAll(PDO::FETCH_ASSOC);

$items = array();

// Ottieni gli oggetti del prodotto
$queryItems = $conn->prepare("SELECT PI.*, I.*
								FROM PS_WebSite.dbo.products_buy AS PI
								LEFT JOIN PS_GameDefs.dbo.Items AS I ON PI.ItemID = I.ItemID
								WHERE PI.product_code=?");
$queryItems->execute([$product["product_code"]]);
$itemsData = $queryItems->fetchAll(PDO::FETCH_ASSOC);

foreach ($itemsData as $item) {
	$id = $item["id"];
	$type = $item["Type"];

	if ($item["CanImprove"] && isset($improvements[$id])) {
		$improvementInfo = $improvements[$id];

		for ($g = 1; $g <= $item["Slot"]; $g++) {
			if ($item["Gem$g"] || !isset($improvementInfo["gem$g"]) || !$improvementInfo["gem$g"])
				continue;

			$gemId = $improvementInfo["gem$g"];
			$gem = array_filter($gems, function ($gem) use ($gemId) {
				return $gem["TypeID"] == $gemId;
			});

			if (empty($gem) || !isset($GemPrice[$gemId]) || canLinkLapis($type, $gem[0])) {
				SetErrorAlert("Purchase error: incorrect gems");
				header("Location:$BackUrl");
				return;
			}

			$item["Gem$g"] = $gemId;
			$totalCost += $GemPrice[$gemId];
		}

		if (isset($improvementInfo["enchant"])) {
			$defaultEnchant = $item["Enchant"];
			$enchant = $improvementInfo["enchant"];
			$equipType = getItemType($type);
			$maxStep = max(array_keys($EnchantPrice[$equipType]));

			if ($enchant < $defaultEnchant || $enchant > $maxStep) {
				SetErrorAlert("Purchase error: incorrect enchant");
				header("Location:$BackUrl");
				return;
			}

			$item["Enchant"] = $equipType == "armor" ? 50 + $enchant : $enchant;

			for ($step = $defaultEnchant + 1; $step <= $enchant; $step++) {
				$totalCost += $EnchantPrice[$equipType][$step];
			}
		}
	}

	$isWarehouseItem = $item["Gem1"] || $item["Gem2"] || $item["Gem3"] || $item["Gem4"] || $item["Gem5"] || $item["Gem6"] || $item["Enchant"];
	$items[] = array("item" => $item, "slot" => 0, "isWarehouseItem" => $isWarehouseItem);
}

// Ottieni i punti dell'utente
$queryPoints = $conn->prepare("SELECT Point FROM PS_Userdata.dbo.Users_Master WHERE UserUID=?");
$queryPoints->execute([$UserUID]);
$point = $queryPoints->fetchColumn();

// Verifica se ci sono abbastanza punti
if ($point < $totalCost) {
	SetErrorAlert("Not enough point");
	header("Location:$BackUrl");
	return;
}

// Aggiungi gli oggetti
foreach ($items as $index => $itemInfo) {
	$item = $itemInfo["item"];
	$isWarehouseItem = $itemInfo["isWarehouseItem"];
	$queryInsertItem = $isWarehouseItem ?
		"INSERT INTO PS_GameData.dbo.UserStoredItems ([ServerID], [UserUID], [ItemID], [Type], [TypeID], [ItemUID], [Slot], [Quality], [Gem1], [Gem2], [Gem3], [Gem4], [Gem5], [Gem6], [Craftname], [Count], [Maketime], [Maketype], [Del])
			VALUES (1, ?, ?, ?, ?, [PS_GameData].[dbo].[ItemUID](), ?, 4000, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), 'X', 0)"
		:
		"INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate)
			VALUES (?, ?, ?, ?, 'Website ItemMall', GETDATE())";

	$queryInsert = $conn->prepare($queryInsertItem);
	$queryInsert->execute([$UserUID, $index, $item["ItemID"], $item["Type"], $item["TypeID"], $index, $item["Gem1"], $item["Gem2"], $item["Gem3"], $item["Gem4"], $item["Gem5"], $item["Gem6"], "000000000000000000" . sprintf('%02d', $item["Enchant"]), $item["ItemCount"]]);
}

// Rimuovi i punti
$queryUpdatePoints = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point=Point-? WHERE UserUID=?");
$queryUpdatePoints->execute([$totalCost, $UserUID]);

// Log
odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.PointLog (UserUID, CharID, UsePoint, ProductCode, UseDate, UseType, OrderNumber) VALUES ($UserUID,0,$totalCost,$productId,GETDATE(),'1',$productId)");	

// Redirect back 
header("Location:/?p=itemmall");