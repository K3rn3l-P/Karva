<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// 
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

// Incorrect ID | Name not exists
if (!isset($_GET["id"], $_GET["name"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Fill all fields");
    header("location: $BackUrl");
    return;
}

$CharID = $_GET["id"];
$CharName = GetClear($_GET["name"]);

// Find char with kills
$SqlRes = odbc_exec($odbcConn, "SELECT UserUID, K1 FROM PS_GameData.dbo.Chars WHERE CharID=$CharID AND Del=0");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Character not exists or deleted");
    header("location: $BackUrl");
    return;
}
$UID = odbc_result($SqlRes, "UserUID");
$K1 = odbc_result($SqlRes, "K1");

// Find target character
$SqlRes = odbc_exec($odbcConn, "SELECT CharID FROM PS_GameData.dbo.Chars WHERE CharName='{$CharName}' AND Del=0");
// Not exists
if (!odbc_num_rows($SqlRes)) {
    SetErrorAlert("Character $CharName not exists or deleted");
    header("location: $BackUrl");
    return;
}
$TargetID = odbc_result($SqlRes, "CharID");

// Add kills to new character
odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.Chars SET K1=K1+$K1 WHERE CharID=$TargetID");
// Remove kills from old character
odbc_exec($odbcConn, "UPDATE PS_GameData.dbo.Chars SET K1=0 WHERE CharID=$CharID");
			
$_SESSION["suc"] = "$K1 kills transfered to $CharName";
header("location: $BackUrl");
