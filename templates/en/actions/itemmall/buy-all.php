<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
	header("Location:$BackUrl");
	return;
}
// Check the Session
if (!isset($_SESSION["products"]) || !count($_SESSION["products"])) {
	SetErrorAlert("Cart is empty!");
	header("Location:$BackUrl");
	return;
}

// Each product
foreach ($_SESSION["products"] as $productId => $productCount) {
	// Find the product
	$result = odbc_exec($odbcConn, "SELECT product_code, product_name, price FROM PS_WebSite.dbo.products WHERE id=$productId");
	if (!odbc_num_rows($result))
		continue;
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
		$itemResult = odbc_exec($odbcConn, "SELECT ItemID, ItemCount FROM PS_Website.dbo.products_buy WHERE product_code='$product[product_code]'");
		$totalCount = odbc_num_rows($itemResult);
		// Get free slots
		$slots = array();
		$slot = 0;
		while (count($slots) < $totalCount) {
			// Bank is full
			if ($slot >= 240) {
				SetErrorAlert("Can't buy the $product[product_name]: bank is full");
				header("location:$BackUrl");
				return;
			}
			$result = odbc_exec($odbcConn, "SELECT 1 FROM PS_Billing.dbo.Users_Product WHERE UserUID=$UserUID AND Slot=$slot");
			// Is free
			if (!odbc_num_rows($result))
				$slots[] = $slot;
			$slot++;
		}
		
		// Add items
		$index = 0;
		while ($item = odbc_fetch_array($itemResult)) {
			$slot = $slots[$index++];
			odbc_exec($odbcConn, "INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate)
							VALUES ($UserUID, $slot, $item[ItemID], $item[ItemCount], 'Website ItemMall', GETDATE())");
		}
		// Remove points
		odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Point-=$product[price] WHERE UserUID=$UserUID");
		// Log
		odbc_exec($odbcConn, "INSERT INTO PS_GameData.dbo.PointLog (UserUID, CharID, UsePoint, ProductCode, UseDate, UseType, OrderNumber) VALUES ($UserUID,0,$product[price],$productId,GETDATE(),'1',$productId)");	
	}	
}

SetSuccessAlert("All items have been purchased");
// Clear cart
unset($_SESSION["products"]);
// Redirect back 
header("Location:$BackUrl");