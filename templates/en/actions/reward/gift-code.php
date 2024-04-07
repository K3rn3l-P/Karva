<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
    header("Location:$BackUrl");
    return;
}

// Incorrect ID
if (!isset($_POST["code"])) {
    SetErrorAlert("Fill the Gift Code!");
    header("location: $BackUrl");
    return;
}
/*
if(isset($_POST['g-recaptcha-response'])){
	$captcha = $_POST['g-recaptcha-response'];
}
$ip = $_SERVER['REMOTE_ADDR'];
$secretkey = "6Le-IkEaAAAAAJG_B7sAk5u10LkkLeQGFCKqjkIC";
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip"),true);
if($response['success'] == false){
	SetErrorAlert("Spam verification failed, please try again!");
	header("location:$BackUrl");
		return;

}
*/
$Code = GetClear($_POST["code"]);
// Find code
$odbcResult = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.GiftCodes WHERE Code='{$Code}' AND Del=0 AND CURRENT_TIMESTAMP<EndDate");
// Code not exists
if (!odbc_num_rows($odbcResult)) {
    SetErrorAlert("Invalid Gift Code!");
    header("location: $BackUrl");
    return;
}
$CodeID = odbc_result($odbcResult, "ID");
$ItemID = odbc_result($odbcResult, "ItemID");
$Count = odbc_result($odbcResult, "Count");
$SP = odbc_result($odbcResult, "SP");
$FEU = odbc_result($odbcResult, "ForEachUser");

// Check is used
$Query = $FEU
	? "SELECT 1 FROM PS_WebSite.dbo.GiftCodes_Log WHERE CodeID=$CodeID AND UserUID=$UserUID"
	: "SELECT 1 FROM PS_WebSite.dbo.GiftCodes_Log WHERE CodeID=$CodeID";
$odbcResult = odbc_exec($odbcConn, $Query);
if (odbc_num_rows($odbcResult)) {
    SetErrorAlert("This Gift Code has already been used!");
    header("location: $BackUrl");
    return;
}

$Reward = "";
// Item used 
if ($ItemID && $Count) {
	// Find free slot
	$odbcResult = odbc_exec($odbcConn, "SELECT Slot FROM PS_Billing.dbo.Users_Product WHERE UserUID={$UserUID} ORDER BY Slot");
	$Slot = 0;
	while ($Arr = odbc_fetch_array($odbcResult)) {
		if ($Arr["Slot"] != $Slot) break;
		$Slot++;
	}
	// Bank is full
	if ($Slot == 240) {
		SetErrorAlert("No free slots in your Bank Teller!");
		header("location: $BackUrl");
		return;
	}
	// Insert item
	odbc_exec($odbcConn, "INSERT INTO PS_Billing.dbo.Users_Product (UserUID, Slot, ItemID, ItemCount, ProductCode, BuyDate) VALUES ({$UserUID},{$Slot},{$ItemID},{$Count},'GiftCode',CURRENT_TIMESTAMP)");
	// Log the using code
	odbc_exec($odbcConn, "INSERT INTO PS_WebSite.dbo.GiftCodes_Log (UserUID,CodeID) VALUES ($UserUID, $CodeID)");
	
	// Get item name
	$odbcResult = odbc_exec($odbcConn, "SELECT ItemName FROM PS_GameDefs.dbo.Items WHERE ItemID=$ItemID");
	$Reward = odbc_result($odbcResult, "ItemName") . " (x$Count)";
}
// SP used
if ($SP) {
	// Add points
	odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Point=Point+$SP WHERE UserUID=$UserUID");
	$Reward .= $Reward ? " and $SP SP" : "$SP SP";
}

SetSuccessAlert("Congratulations! You got:  $Reward");
header("location: $BackUrl");