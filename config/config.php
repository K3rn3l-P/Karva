<?php
session_start();
// Show exceptions
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Connessione al database PS_UserData
$dbuser = getenv('DB_USER');
$dbpass = getenv('DB_PASSWORD');

$odbcConn = @odbc_connect("Driver={SQL Server};Server=127.0.0.1;", $dbuser, $dbpass) or die ("Connessione al database non riuscita: " . odbc_errormsg());

// Inizializza la connessione PDO
try {
    $conn = new PDO('sqlsrv:Server=127.0.0.1;Database=PS_UserData', $dbuser, $dbpass);
    // Imposta PDO per generare un'eccezione in caso di errore
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gestisci l'eccezione in caso di errore di connessione al database
    die("Connessione al database non riuscita: " . $e->getMessage());
}

// Website pages
$pages = array(
	"news"
	,"download"
	,"login"
	,"register"
	,"password-recovery"
	,"settings"
	,"ucp"
	,"ref"
	,"contact"
	,"support"
	,"termsofservice"
	,"refundpolicy"
	
	// Game section
	,"game"
	
	,"events"
	,"custom-ranking-system"
	,"ranks"
	,"boss-records"
	,"guilds"
	,"droplist"
	,"drops"
	
	,"resources"
	,"video"
	,"screenshots"
	,"wallpaper"
	
	,"itemmall"
	,"billing"
	,"history"
	,"stripe"
	
	,"pvp-reward"
	,"grb-reward"
	,"tiered"
	,"vote"
	,"gift-code"
	,"forge"
	,"forge-vp"
	,"collectors"
	,"daily-reward"
	,"lastkills"
	
	,"gm-panel-n3w"
);
	
// Get current page
$page = isset($_GET["p"]) && in_array($_GET["p"], $pages, true) 
			? $_GET["p"]
			: $pages[0];
			
// Array of languages (templates)
$Languages = array(
	"en" => "Shaiya Duff!",
	
	
	
	
);
// Change language (template)
if (isset($_GET["lang"]) && array_key_exists($_GET["lang"], $Languages)) 
	$_SESSION["lang"] = $_GET["lang"];
// Load language (template) - used for TemplateUrl
$lang = isset($_SESSION["lang"]) && array_key_exists($_SESSION["lang"], $Languages) 
			? $_SESSION["lang"]
			: key($Languages);
						
// GM panel only english
if ($page == "gm-panel-n3w")
	$lang = "en";



// IP запроса
$user_ip = $_SERVER['REMOTE_ADDR'];
$UserIP = $_SERVER['REMOTE_ADDR'];
$userip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$userip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$userip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$userip = $_SERVER['REMOTE_ADDR'];
}



// Referral info
if (isset($_GET["ref"]) && is_numeric($_GET["ref"])) {
	$_SESSION["ref"] = $_GET["ref"];
}

$HomeUrl = "https://s.pinto-lime.ts.net/";
$BackUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $HomeUrl;
$TemplateUrl = "templates/$lang/"; // Link to current template
$AssetUrl = "$HomeUrl"; // Link to folder where placed images, css, js folders
$PageUrl = "$TemplateUrl/pages/$page/"; // Link to current page folder

include_once('function.php');	//Функции
include_once('maps.php');		//Карты
include_once('arrays.php');		//Массивы

// Server name
$ServerName = "Shaiya Duff!";
// Currency name
$currencyCode = "SP";
$currencyName = "Shaiya Points";
$currencyCode2 = "VP";
$currencyName2 = "Vote Points";
// Social
$DiscordUrl = "https://discord.gg/shaiyaduff";
$FacebookUrl = "https://www.facebook.com/shaiyaduff";
$VkUrl = "#";
$YoutubeUrl = "#";



// Download links
$DownloadLinks = array(
	"en" => array(
		"mega" => "https://mega.nz/file/edAWBSoI#4IwaqOMstAxHgANLJYRjfaFzCUcC-QjGfg6GQSqubqw",
		"drive" => "https://drive.google.com/file/d/1KzCev2hYDM5Le96IfsQf2tymGzXKf_Ad/view?usp=sharing",	
		"mediafire" => "https://www.mediafire.com/file/g996gbalztqurzr/Shaiya_Elixir_2022.rar/file",

	
	),
	 "de" => array(
		"mega" => "#",
		"drive" => "#",
		"mediafire" => "#",		
	 ),
	 "pt" => array(
		"mega" => "#",
		"drive" => "#",
		"mediafire" => "#",			
	 ),
);

// Текущая дата
$GetDate = date('d.m.y');

// Referal
if(isset($_GET["ref"]) && is_numeric($_GET["ref"]))
	$_SESSION["ref"] = GetClear($_GET["ref"]);

//Данные о пользователе
$UserUID = 0;
$UserID = "";
$Point = 0;
$VotePoint = 0;
$Status = 0;
$IsGM = false;
$IsStaff = false;
if (isset($_SESSION["session_id"], $_SESSION["UserUID"]) && is_numeric($_SESSION["UserUID"])) {
	$query = "SELECT TOP 1 UM.UserUID, UM.UserID, UM.Point, UM.VotePoint, UM.Status, UM.AdminLevel, UM.Email, UMG.Country, C.CharName, ISNULL(C.K1, 0) AS K1 FROM PS_UserData.dbo.Users_Master UM 
				LEFT JOIN PS_GameData.dbo.UserMaxGrow UMG ON UMG.UserUID=UM.UserUID 
				LEFT JOIN PS_GameData.dbo.Chars C ON C.UserUID=UM.UserUID AND C.Del=0
				WHERE UM.UserUID=$_SESSION[UserUID]
				ORDER BY C.K1 DESC";
	$result = odbc_exec($odbcConn, $query);
	if (odbc_num_rows($result)) {
		$UserInfo = odbc_fetch_array($result);		
		$UserUID = $UserInfo["UserUID"];
		$Status = $UserInfo["Status"];
		$Faction = $UserInfo["Country"];
		$UserID = $UserInfo["UserID"];
		$Point = $UserInfo["Point"];
		$VotePoint = (int)$UserInfo["VotePoint"];
		$IsGM = $Status == 16;
		$IsStaff = $UserInfo["AdminLevel"] >= 200;
	} else {
		session_destroy();
	}
}