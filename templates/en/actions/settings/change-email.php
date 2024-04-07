<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
    header("Location:$BackUrl");
    return;
}

if (!isset($_POST["email"]) || empty($_POST["email"])) {
	SetErrorAlert("Fill the e-mail address");
	header("Location:$BackUrl");
	return;
}

// Check the email
$email = GetClear($_POST['email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	SetErrorAlert("Email not valid");
	return;
} if (strlen($email) > 30) {
	SetErrorAlert("Email too big");
	return;
}


// Проверить пароль
$result = odbc_exec($odbcConn, "SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE Email='$email'");
if (odbc_num_rows($result)) {
	SetErrorAlert("Email already in use");
	header("Location:$BackUrl");
	return;
}

// Update email
$result = odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Email='$email' WHERE UserUID=$UserUID");
$result ? SetSuccessAlert("Email successfully changed") : SetErrorAlert("Email changing error");
header("Location:$BackUrl");
