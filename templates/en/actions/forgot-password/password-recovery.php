<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

$verificationFailed = 0;
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

    else
    */ 
     {
            $id = $_POST['userid'];
            $queryAnswer = $conn->prepare('
                SELECT p.UserUID, p.PIN
                FROM PS_UserData.dbo.Users_PIN p
                JOIN PS_UserData.dbo.Users_Master m ON p.UserUID  = m.UserUID
                WHERE m.UserID=?');
            $queryAnswer->bindParam(1, $id, PDO::PARAM_STR);
            $queryAnswer->execute();
            $row = $queryAnswer->fetch(PDO::FETCH_NUM);
            $uid = $row[0];
            $pwd = $row[1];

            if ($pwd != $_POST['answer']) {
                $verificationFailed = 1;
            
                    SetErrorAlert("Secret Key Not matched");
                    header("Location:$BackUrl");
                    return;
            } else {
                $queryID = $conn->prepare('SELECT Pw FROM PS_UserData.dbo.Users_Master WHERE UserUID=?');
                $queryID->bindParam(1, $uid, PDO::PARAM_STR);
                $queryID->execute();
                $pass = $queryID->fetch(PDO::FETCH_NUM);
               
            // echo "<p class='text-center'>&#x2705; Password account is: <strong style='margin: 0px; padding: 0px; color:green;'>" . $pass[0] . "</strong><br /> <a href='/?p=login'>Back to login page</a>.</p>";
            SetSuccessAlert("<p class='text-center'>Password account is: <strong style='margin: 0px; padding: 0px; color:green;'>" . $pass[0] . "</strong><br /> <a href='/?p=login'>Back to login page</a></p>");

                header("Location:$BackUrl");
            }
        }