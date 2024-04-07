<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}


// Check values
if (!isset($_POST["product"]) || !is_numeric($_POST["product"])) {
	header("Location:$BackUrl");
	return;
}
$productId = $_POST["product"];
$improvements = isset($_POST["item"]) && is_array($_POST["item"]) ? $_POST["item"] : array();

// Find the product
$result = odbc_exec($odbcConn, "SELECT product_code, product_name, price FROM PS_WebSite.dbo.products WHERE id=$productId");
if (!odbc_num_rows($result)) {
	SetErrorAlert("Product not exist");
	header("Location:$BackUrl");
	return;
}
$product = odbc_fetch_array($result);
$cost = $product["price"];
$totalCost = $cost;

// Get server gems 
$result = odbc_exec($odbcConn, "SELECT * FROM PS_GameDefs.dbo.Items WHERE Type=30");
$Gems = array();
while ($gem = odbc_fetch_array($result)) {
	$typeId = $gem["TypeID"];
	$Gems[$typeId] = $gem;
}

// Get product items
$itemResult = odbc_exec($odbcConn, "SELECT PI.*, I.*
									FROM PS_WebSite.dbo.products_buy [PI]
									LEFT JOIN PS_GameDefs.dbo.Items [I] ON [PI].ItemID=[I].ItemID
									WHERE product_code='$product[product_code]'");
$items = array();
while ($item = odbc_fetch_array($itemResult)) {
	$id = $item["id"];
	$type = $item["Type"];
	if ($item["CanImprove"] && array_key_exists($id, $improvements)) {
		$improvementInfo = $improvements[$id];
		
		// Each gem slot
		for ($g = 1; $g <= $item["Slot"]; $g++) {
			// Lapis already linked (native) or not selected for this slot
			if ($item["Gem$g"] || !isset($improvementInfo["gem$g"]) || !$improvementInfo["gem$g"])
				continue;

			$gemId = $improvementInfo["gem$g"];
			$gem = $Gems[$gemId];
			// Can't link this lapis
			if (!array_key_exists($gemId, $GemPrice) || canLinkLapis($type, $gem)) {
				SetErrorAlert("Purchase error: incorrect gems");
				header("Location:$BackUrl");
				return;
			}
			
			$item["Gem$g"] = $gemId;
			$totalCost += $GemPrice[$gemId];
		}
		// Enchant selected
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
			// Add enchant cost
			for ($step = $defaultEnchant + 1; $step <= $enchant; $step++) {
				$totalCost += $EnchantPrice[$equipType][$step];
			}
		}
	}
	$isWarehouseItem = $item["Gem1"] || $item["Gem2"] || $item["Gem3"] || $item["Gem4"] || $item["Gem5"] || $item["Gem6"] || $item["Enchant"];
	$items[] = array("item" => $item, "slot" => 0, "isWarehouseItem" => $isWarehouseItem);
}

// Get points
$result = odbc_exec($odbcConn, "SELECT Point FROM PS_Userdata.dbo.Users_Master WHERE UserUID=$UserUID");
$point = odbc_result($result, "Point");
// Not enough
if ($point < $totalCost) {
	SetErrorAlert("Not enough point");
	header("Location:$BackUrl");
	return;
}

// Bank items
$slot = 0;
for ($j = 0; $j < count($items); $j++) {
	$itemInfo = $items[$j];
	// Not bank item
	if ($itemInfo["isWarehouseItem"])
		continue;
	// Find free slot
	while (true) {
		// Bank is full
		if ($slot >= 240) {
			SetErrorAlert("Can't buy the $product[product_name]: bank is full");
			header("location:$BackUrl");
			return;
		}
		$result = odbc_exec($odbcConn, "SELECT 1 FROM PS_Billing.dbo.Users_Product WHERE UserUID=$UserUID AND Slot=$slot");
		// Is free
		if (!odbc_num_rows($result)) 
			break;
		$slot++;
	}
	// Set new slot
	$items[$j]["slot"] = $slot;
	$slot++;
}

// Warehouse items
$slot = 0;
for ($j = 0; $j < count($items); $j++) {
	$itemInfo = $items[$j];
	// Not warehouse item
	if (!$itemInfo["isWarehouseItem"])
		continue;
	// Find free slot
	while (true) {
		// Bank is full
		if ($slot >= 240) {
			SetErrorAlert("Can't buy the $product[product_name]: warehouse is full");
			header("location:$BackUrl");
			return;
		}
		$result = odbc_exec($odbcConn, "SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE UserUID=$UserUID AND Slot=$slot");
		// Is free
		if (!odbc_num_rows($result)) 
			break;
		$slot++;
	}
	// Set new slot
	$items[$j]["slot"] = $slot;
	$slot++;
}

// Add items
foreach ($items as $itemInfo) {
	$item = $itemInfo["item"];
	$slot = $itemInfo["slot"];
	$isWarehouseItem = $itemInfo["isWarehouseItem"];
	
	$itemId = $item["ItemID"];
	$type = floor($itemId / 1000);
	$typeId = $itemId % 1000;
	$craftname = "000000000000000000" . sprintf('%02d', $item["Enchant"]);
	
	$query = $isWarehouseItem
			? "INSERT INTO [PS_GameData].[dbo].[UserStoredItems] ([ServerID],[UserUID],[ItemID],[Type],[TypeID],[ItemUID],[Slot],[Quality],[Gem1],[Gem2],[Gem3],[Gem4],[Gem5],[Gem6],[Craftname],[Count],[Maketime],[Maketype],[Del])
					VALUES (1, $UserUID, $itemId, $type, $typeId, [PS_GameData].[dbo].[ItemUID](), $slot, 4000, $item[Gem1], $item[Gem2], $item[Gem3], $item[Gem4], $item[Gem5], $item[Gem6], '$craftname', $item[ItemCount], GETDATE(), 'X', 0)"
			: "INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate)
					VALUES ($UserUID, $slot, $itemId, $item[ItemCount], 'Website ItemMall', GETDATE())";
	odbc_exec($odbcConn, $query);
	// Notify user
	$slot++;
	SetSuccessAlert($isWarehouseItem ? "Item <b>$item[ItemName]</b> added to warehouse ($slot slot)" : "Item <b>$item[ItemName]</b> added to bank ($slot slot)");
}
// Remove points
odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Point-=$totalCost WHERE UserUID=$UserUID");
// Log
odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.PointLog (UserUID, CharID, UsePoint, ProductCode, UseDate, UseType, OrderNumber) VALUES ($UserUID,0,$totalCost,$productId,GETDATE(),'1',$productId)");	

// Redirect back 
header("Location:/?p=itemmall");