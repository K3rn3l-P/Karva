<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
if (!$UserUID) {
    header("Location:$BackUrl");
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
	header("Location:$BackUrl");
	return;
} 
*/
// Проверка наличия POST
$posts = array("old-password","new-password","password-confirm");
foreach ($posts as $post) {
	if (!isset($_POST[$post]) || empty($_POST[$post])) {
		SetErrorAlert("Fill all the fields");
		header("Location:$BackUrl");
		return;
	}
}

$oldpassword = GetClear($_POST['old-password']);
$password = GetClear($_POST['new-password']);
$passwordConfirm = GetClear($_POST['password-confirm']);

if (strlen($password) < 5 || strlen($password) > 15)
	SetErrorAlert("Password must be 5-15 in length");
if ($password <> $passwordConfirm)
	SetErrorAlert("Passwords do not match");
if ($UserID === $password)
	SetErrorAlert("Password must be different from login");
if (!preg_match('/^[a-zA-Z0-9]{5,15}+$/', $password))
	SetErrorAlert("Password must contains only letters and numbers");

if (anyErrors()) {
	header("Location:$BackUrl");
	return;
}

// Проверить пароль
$result = odbc_exec($odbcConn, "SELECT 1 FROM PS_UserData.dbo.Users_Master WHERE UserUID=$UserUID AND Pw='$oldpassword'");
if (!odbc_num_rows($result)) {
	SetErrorAlert("Wrong password");
	header("Location:$BackUrl");
	return;
}

// Обновить пароль
$result = odbc_exec($odbcConn, "UPDATE PS_UserData.dbo.Users_Master SET Pw='$password' WHERE UserUID=$UserUID AND Pw='$oldpassword'");
$result ? SetSuccessAlert("Password successfully changed") : SetErrorAlert("Password changing error");
header("Location:$BackUrl");
