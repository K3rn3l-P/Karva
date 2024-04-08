<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Check if user is staff
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

$username = isset($_POST["username"]) ? GetClear($_POST['username']) : "";
$charname = isset($_POST["charname"]) ? GetClear($_POST['charname']) : "";
$forAll = isset($_POST["feu"]) ? 1 : 0;
$count = $_POST['count'];
$itemid = $_POST['itemid'];

// Validate input fields
if ((!$username && !$charname && !$forAll) || !is_numeric($count) || !is_numeric($itemid)) {
    SetErrorAlert("Fill all fields");
    header("location:$BackUrl");
    return;
}
if (!$count || $count > 255) {
    SetErrorAlert("Wrong item count");
    header("location:$BackUrl");
    return;
}
if ($itemid < 1001 || $itemid > 255255) {
    SetErrorAlert("Wrong ItemID");
    header("location:$BackUrl");
    return;
}

// Prepare and execute query to retrieve item name
$query = $conn->prepare("SELECT ItemName FROM PS_GameDefs.dbo.Items WHERE ItemID = ?");
$query->execute([$itemid]);
$row = $query->fetch(PDO::FETCH_ASSOC);

// Check if item exists
if (!$row) {
    $label = number_format($itemid, 0, '.', ' ');
    SetErrorAlert("Item $label does not exist");
    header("location:$BackUrl");
    return;
}
$itemName = $row['ItemName'];

// Include relevant file based on input criteria
if ($username) {
    include_once("by-username.php");
} elseif ($charname) {
    include_once("by-charname.php");
} elseif ($forAll) {
    include_once("for-all.php");
}

header("location:$BackUrl");
?>
