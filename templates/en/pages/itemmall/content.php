<?php
if (!$UserUID) {
	include("pages/not-logged.php");
	return;
}
// Adding product
if (isset($_GET["admin"]) && $IsGM) {
	$title = 'Create the product';
	include("pages/admin.php");
	return;
}
// Improving product
if (isset($_GET["improve"])) {
	$title = 'Improve item before purchase';
	include("pages/improve.php");
	return;
}

// Category
$category = isset($_GET["category"]) && is_numeric($_GET["category"]) ? $_GET["category"] : 0;

switch ($category) {
	case 2:
		$title = 'Weapon Skins';
		break;
	case 1:
		$title = 'Services';
		break;
	case 0:
		$title = 'Mounts';
		break;	
	case 3:
		$title = 'Stigma Item Skills';
		break;	
	case 4:
		$title = 'Football collection';
		break;		
	case 5:
		$title = 'Equipments Zone 60 PvP';
		break;
	case 6:
		$title = 'Equipments Zone 30 PvP';
		break;
	case 7:
		$title = 'Equipments Zone 15 PvP';
		break;
	case 8:
		$title = 'Weapons Zone 15 PvP';
		break;	
	case 9:
		$title = 'Weapons Zone 30 PvP';
		break;
	case 77677:
		$title = 'Hidden IM Gears';
		break;
			
		
}

// Show products or cart
include("pages/products.php");
