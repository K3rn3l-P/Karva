<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

if (!$IsStaff) {
    header("Location: $BackUrl");
    exit();
}

if (!isset($_GET["id"], $_GET["name"]) || !is_numeric($_GET["id"])) {
    SetErrorAlert("Fill all fields");
    header("Location: $BackUrl");
    exit();
}

$CharID = $_GET["id"];
$CharName = GetClear($_GET["name"]);

$query = $conn->prepare("SELECT UserUID, K1 FROM PS_GameData.dbo.Chars WHERE CharID = ? AND Del = 0");
$query->execute([$CharID]);
$row = $query->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    SetErrorAlert("Character does not exist or has been deleted");
    header("Location: $BackUrl");
    exit();
}

$UID = $row["UserUID"];
$K1 = $row["K1"];

$query = $conn->prepare("SELECT CharID FROM PS_GameData.dbo.Chars WHERE UserUID = ? AND CharName = ? AND Del = 0");
$query->execute([$UID, $CharName]);
$row = $query->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    SetErrorAlert("Character $CharName does not exist or has been deleted");
    header("Location: $BackUrl");
    exit();
}

$TargetID = $row["CharID"];

$query = $conn->prepare("UPDATE PS_GameData.dbo.Chars SET K1 = K1 + ? WHERE CharID = ?");
$query->execute([$K1, $TargetID]);

$query = $conn->prepare("UPDATE PS_GameData.dbo.Chars SET K1 = 0 WHERE CharID = ?");
$query->execute([$CharID]);

SetSuccessAlert("$K1 kills transferred to $CharName");

$query = $conn->prepare("INSERT INTO PS_WebSite.dbo.AdminLog (UserUID, UserID, Action, Text, IP) VALUES (?, ?, ?, ?, ?)");
$query->execute([$UserUID, $UserID, 'Transfer kills', "FROM CHARID: $CharID; TO: $CharName", $UserIP]);

header("Location: $BackUrl");
exit();
?>
