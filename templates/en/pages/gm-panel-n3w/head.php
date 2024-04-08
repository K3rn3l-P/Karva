<?php
// Access only for staff
if (!$IsStaff) {
	header("location:$HomeUrl");
	exit;
}

// Page name used for no access notify
$noAccessPage = "no-access";
// Pages of GM-Panel
$subpages = array(
	"users-online" => array(
		"Title" => "Users online", 
		"AdminLevel" => 200,
	),
	
	"user-search" => array(
		"Title" => "Find user", 
		"AdminLevel" => 200,
	),
	"user-management" => array(
		"Title" => "User management", 
		"AdminLevel" => 200,
	),
	"ban" => array(
		"Title" => "Ban user", 
		"AdminLevel" => 200,
	),
		
	
	"kill-transfer-global" => array(
		"Title" => "Transfer kills", 
		"AdminLevel" => 255,
	),
	"item-restore" => array(
		"Title" => "Restore broken items", 
		"AdminLevel" => 200,
	),
	"dropped-items" => array(
		"Title" => "Restore dropped items", 
		"AdminLevel" => 200,
	),
	"selled-items" => array(
		"Title" => "Restore selled NPC items", 
		"AdminLevel" => 255,
	),
	"inventory-management" => array(
		"Title" => "Inventory management [edit item]", 
		"AdminLevel" => 255,
	),
	"item-fc" => array(
		"Title" => "Item FC", 
		"AdminLevel" => 200,
	),
	"guild-management" => array(
		"Title" => "Guild management", 
		"AdminLevel" => 200,
	),
	"quest-management" => array(
		"Title" => "Quest management", 
		"AdminLevel" => 200,
	),
	
	"kills" => array(
		"Title" => "Kills logs", 
		"AdminLevel" => 200,
	),

	
	"logs" => array(
		"Title" => "Logs viewer", 
		"AdminLevel" => 200,
	),
	
	"gm-panel-logs" => array(
		"Title" => "GM-Panel logs", 
		"AdminLevel" => 255,	
	),
	"giftbox-add" => array(
		"Title" => "Giftbox add", 
		"AdminLevel" => 255,
	),
	"auction" => array(
		"Title" => "Auction board", 
		"AdminLevel" => 200,
	),
	"kick" => array(
		"Title" => "kick / unlock stacked player", 
		"AdminLevel" => 100,
	),
	
	"add-gm" => array(
		"Title" => "ADD / REMOVE GM", 
		"AdminLevel" => 255,
	),
	"restore" => array(
		"Title" => "Restore Deleted character", 
		"AdminLevel" => 200,	
	),
	"webmall-logs" => array(
		"Title" => "Purchases Web-Mall logs", 
		"AdminLevel" => 200,	
	),
	"itemmall-logs" => array(
		"Title" => "Purchases Item-Mall logs", 
		"AdminLevel" => 200,	
	),
	
	
	"payment-logs" => array(
		"Title" => "Payment Logs", 
		"AdminLevel" => 255,	
	),
	
	"info0" => array(
		"Title" => "Info - Commands", 
		"AdminLevel" => 201,	
	),
	
	"info1" => array(
		"Title" => "Info - Bosses getitem", 
		"AdminLevel" => 201,	
	),
	
	"info2" => array(
		"Title" => "Info - Bosses mmake", 
		"AdminLevel" => 201,	
	),
	
	"info3" => array(
		"Title" => "Info - NPC nmake", 
		"AdminLevel" => 201,	
	),
	"info4" => array(
		"Title" => "Info - MapID", 
		"AdminLevel" => 201,	
	),
	
	"item-edit" => array(
		"Title" => " ", 
		"AdminLevel" => 255,	
	),
	
	"gift-code" => array(
		"Title" => " ", 
		"AdminLevel" => 255,	
	),
);

// Get current subpage
$subpage = isset($_GET["sp"]) && array_key_exists($_GET["sp"], $subpages)
			? $_GET["sp"]
			: key($subpages);
			
// Required admin level for access to subpage
$requiredLevel = $subpages[$subpage]["AdminLevel"];
// No access
if ($UserInfo["AdminLevel"] < $requiredLevel) {
	$subpage = "no-access";
}
?>
<?php include_once("$PageUrl/pages/$subpage/head.php") ?>
<?php include_once("$TemplateUrl/modules/head.php") ?>
<link type="text/css" rel="stylesheet" href="<?= $PageUrl ?>css/game.css"/>
