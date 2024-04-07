<?php

session_start();  
include  "stripe-php/init.php";


// LIVE GODDESS SECRET KEY
$stripeAPiKey = "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";
	
	$isSuccess = true;
	$points  = 0;
	$SuccessMessage = "";

	$entityBody = file_get_contents('php://input');
	$request = json_decode($entityBody);

    //$mUserID = 1;
	$mUserID =  $request->userId;

 	header('Content-Type: application/json');

	switch ($request->price) {
         
		  
		  case 5:
            $points = 500;
            break;

          case 10:
            $points = 1100;
            break;
          
          case 25:
            $points = 3000;
            break;
          
          case 50:
            $points = 6500;
            break;
          
          case 100:
            $points = 13000;
            break;
          
          case 150:
            $points = 21000;
            break;
          
          case 200:
            $points = 30000;
            break;
          
          default:
             $points = 500;
            break;
        }

    if($isSuccess == true)
    {

		$transactionDetails = "[UserUID_".$mUserID."] We've credited ".$points." Shaiya Points to your account!";

		
  	    include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
  		
		$ref =$request->payment_intent_id;
    	$method = 'stripe';
    	$time = date('Y-m-d H:i:s');

		$ip = ip2long($userip);
		$checkMin = ip2long('216.127.71.0');
		$checkMax = ip2long('216.127.71.255');

		$querytxn = $conn->prepare('SELECT Ref FROM PS_WebSite.dbo.Payments WHERE Ref= ? AND mUserID = ?');
		$querytxn->bindValue(1, $ref, PDO::PARAM_INT);
		$querytxn->bindValue(2, intval($mUserID), PDO::PARAM_INT);
		$querytxn->execute();
		$txn = $querytxn->FETCH(PDO::FETCH_NUM);

        $useridok =intval($mUserID);

        if ($txn[0] == NULL)
        {
			try
			{
				// Adding SP Currency in user account
				$query = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point += ? WHERE UserUID = ?");
				$query->bindValue(1, $points, PDO::PARAM_INT);
				$query->bindValue(2, $useridok, PDO::PARAM_INT);
				$query->execute();

				$status =  'Delivered';
				
				// Save STRIPE Payment History
                $query1 = $conn->prepare("INSERT INTO PS_WebSite.dbo.Payments (Ref, uid, point, method, date, status) VALUES (?, ?, ?, ?, ?, 'Delivered')");
				$query1->bindValue(1, $request->payment_intent_id, PDO::PARAM_INT);
				$query1->bindValue(2, $useridok, PDO::PARAM_INT);
				$query1->bindValue(3, $points, PDO::PARAM_INT);
				$query1->bindValue(4, $method, PDO::PARAM_INT);
				$query1->bindValue(5, $time, PDO::PARAM_INT);
				$query1->bindValue(6, $status, PDO::PARAM_INT);
				$query1->execute();

				// SAVE USER Billing Information
				$queryBillingInfo = $conn->prepare("INSERT INTO PS_WebSite.dbo.UserBillingInformation (CustomerName, Address, City, State, PostalCode, PhoneType, Phone, Email, UserID) VALUES (?, ?, ?, ?, ?, ?, ? , ? , ? )");
				$queryBillingInfo->bindValue(1,$request->firstname.' '.$request->lastname, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(2, $request->address, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(3, $request->city, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(4, $request->state, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(5, $request->postalcode, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(6, $request->phonetype, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(7, $request->phone, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(8, $request->email, PDO::PARAM_INT);
				$queryBillingInfo->bindValue(9, $useridok, PDO::PARAM_INT);
				$queryBillingInfo->execute();

			} catch(Exception $e)
			{
				$isSuccess = false;
				
			}	
		}
		else
		{
			$isSuccess = false;
			
		}

	}

    if($isSuccess)
    {
    	$SuccessMessage = "We've credited ".strval($points)." Shaiya Points to your account!";
	    echo json_encode(array("data"=>"true", "message"=>$SuccessMessage));

		
    } 
    else
    {
		echo json_encode(array("data"=>"false", "message" => "Something went wrong"));
		
	}
	

?>