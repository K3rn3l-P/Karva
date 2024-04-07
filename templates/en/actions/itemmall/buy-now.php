<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}
// Check values
if (!isset($_POST["id"], $_POST["count"]) || !is_numeric($_POST["id"]) || !is_numeric($_POST["count"])) {
	header("Location:$BackUrl");
	return;
}
$productId = $_POST["id"];
$productCount = $_POST["count"];

// Find the product
$result = odbc_exec($odbcConn, "SELECT product_code, product_name, price FROM PS_WebSite.dbo.products WHERE id=$productId");
if (!odbc_num_rows($result)) {
	SetErrorAlert("Product not exist");
	header("Location:$BackUrl");
	return;
}
$product = odbc_fetch_array($result);
$cost = $product["price"] * $productCount;

// Get points
$result = odbc_exec($odbcConn, "SELECT Point FROM PS_Userdata.dbo.Users_Master WHERE UserUID=$UserUID");
$point = odbc_result($result, "Point");
// Not enough
if ($point < $cost) {
	SetErrorAlert("Not enough point");
	header("Location:$BackUrl");
	return;
}

for ($i = 0; $i < $productCount; $i++) {
	// Get product items
	$itemResult = odbc_exec($odbcConn, "SELECT PI.*, I.*
									FROM PS_WebSite.dbo.products_buy [PI]
									LEFT JOIN PS_GameDefs.dbo.Items [I] ON [PI].ItemID=[I].ItemID
									WHERE product_code='$product[product_code]'");
	$items = array();
	while ($item = odbc_fetch_array($itemResult)) {
		if ($item["CanImprove"]) {
			header("Location:/?p=itemmall&improve=$productId");
			return;
		}
		$isWarehouseItem = $item["Gem1"] || $item["Gem2"] || $item["Gem3"] || $item["Gem4"] || $item["Gem5"] || $item["Gem6"] || $item["Enchant"];
		$items[] = array("item" => $item, "slot" => 0, "isWarehouseItem" => $isWarehouseItem);
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
		// Update enchant for armor
		if (getItemType($type) == "armor")
			$item["Enchant"] += 50;
		// Set craftname
		$craftname = "000000000000000000" . sprintf('%02d', $item["Enchant"]);
		
		$query = $isWarehouseItem
				? "INSERT INTO [PS_GameData].[dbo].[UserStoredItems] ([ServerID],[UserUID],[ItemID],[Type],[TypeID],[ItemUID],[Slot],[Quality],[Gem1],[Gem2],[Gem3],[Gem4],[Gem5],[Gem6],[Craftname],[Count],[Maketime],[Maketype],[Del])
						VALUES (1, $UserUID, $itemId, $type, $typeId, [PS_GameData].[dbo].[ItemUID](), $slot, 4000, $item[Gem1], $item[Gem2], $item[Gem3], $item[Gem4], $item[Gem5], $item[Gem6], '$craftname', $item[ItemCount], GETDATE(), 'X', 0)"
				: "INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate)
						VALUES ($UserUID, $slot, $itemId, $item[ItemCount], 'Website ItemMall', GETDATE())";
		odbc_exec($odbcConn, $query);
		
		$slot++;
		SetSuccessAlert($isWarehouseItem ? "Item <b>$item[ItemName]</b> added to warehouse ($slot slot)" : "Item <b>$item[ItemName]</b> added to bank ($slot slot)");
	}
	// Remove points
	odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Point-=$product[price] WHERE UserUID=$UserUID");
	// Log
	odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.PointLog (UserUID, CharID, UsePoint, ProductCode, UseDate, UseType, OrderNumber) VALUES ($UserUID,0,$product[price],$productId,GETDATE(),'1',$productId)");	
}

// Redirect back 
SetSuccessAlert("Product have been purchased");
header("Location:$BackUrl");