<?php
 include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
   
 if ($_POST['payment_status'] != 'Completed'  || !isset($_POST['txn_id']) ) {
	
  //header("location:/");
  die();
}
$product_price = $_POST['mc_gross'];

$points = 0;

switch ($product_price) {
    


	case 10.00:
      $points = 1000;
      break;

    case 25.00:
      $points = 2750;
      break;
    
    case 50.00:
      $points = 6000;
      break;
    
    case 100.00:
      $points = 13000;
      break;
    
    case 200.00:
      $points = 28000;
      break;
    
    case 300.00:
      $points = 45000;
      break;
    

    
    default:
       $points = 1000;
      break;
  }

  $transactionDetails = "[UserUID_".$UserUID."] We've credited ".$points." Shaiya Points to your account!";
  $querytxn = $conn->prepare('SELECT Ref FROM PS_WebSite.dbo.Payments WHERE Ref= ?');
//   $querytxn->bindValue(1, $ref, PDO::PARAM_INT);
//  // $querytxn->bindValue(2, intval($UserUID), PDO::PARAM_INT);
//   $querytxn->execute();
//   $txn = $querytxn->FETCH(PDO::FETCH_NUM);



 $method = 'Paypal';
$time = date('Y-m-d H:i:s');

$useridok = $_GET['user_id'];

//$ref = "notify payment (Amount : ".$_POST['mc_gross'].", Transaction Id : ".$_POST['txn_id']." , Status of Payment : ".$_POST['payment_status'].", User Id : ".$_GET['user_id'].")";

$ref =  $_POST['txn_id'];

$query = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point += ? WHERE UserUID = ?");
$query->bindValue(1, $points, PDO::PARAM_INT);
$query->bindValue(2, $useridok, PDO::PARAM_INT);
$query->execute();

$query1 = $conn->prepare("INSERT INTO PS_WebSite.dbo.Payments (Ref, uid, point, method, date, status) VALUES (?, ?, ?, ?, ?, 'Delivered')");
$query1->bindValue(1, $ref , PDO::PARAM_INT);
$query1->bindValue(2, $useridok, PDO::PARAM_INT);
$query1->bindValue(3, $points, PDO::PARAM_INT);
$query1->bindValue(4, $method, PDO::PARAM_INT);
$query1->bindValue(5, $time, PDO::PARAM_INT);

$query1->execute();
?>