<?php

session_start();  
include  "stripe-php/init.php";


// GODDESS SECRET KEY
$stripeAPiKey = "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";


    $message = ''; 
	$isSuccess = true;
	$customerResponse;
	$paymentIntent;

	$entityBody = file_get_contents('php://input');
	$request = json_decode($entityBody);

	$mUserID =  $request->userId;
  
	\Stripe\Stripe::setApiKey($stripeAPiKey);

	$stripe = new \Stripe\StripeClient($stripeAPiKey);
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
    
    $transactionDetails = "[UserUID_".$mUserID."] We've credited ".$points." Shaiya Points to your account!";

	try{
        
		$address = array("city"=> $request->city, "country"=>  $request->country, "line1"=> $request->address, 
		"postal_code" => $request->postalcode, "state"=>$request->sector);
		$customerResponse = $stripe->customers->create([
			'email' => $request->email,
			'name' => $request->firstname.' '.$request->lastname,
			'phone' => $request->phone,
			'address' => $address,
			'description' => "Purchased SP of amount €".$request->price,
		  ]);
	} catch(Exception $e) { 
        $message = $e->getMessage();
		$isSuccess = false;
	} 

	if($isSuccess){
	 try { 

		$paymentIntent = \Stripe\PaymentIntent::create([
			'amount' => $request->price*100,
			'currency' => 'eur',
			'setup_future_usage' => 'off_session',
			'payment_method_types' => ['card'],
			'statement_descriptor' => "SP of amount €".$request->price,
			'description' => $transactionDetails,
			'customer' => $customerResponse->id,

		]);
		
        } catch(Exception $e) { 
            $message = $e->getMessage();
            $isSuccess = false;
        } 
	}

    if($isSuccess)
    {
	    echo json_encode(array("data"=>"true", "secret" => $paymentIntent->client_secret, "customer"=>$customerResponse->id));
		
    } 
    else
    {
		echo json_encode(array("data"=>"false", "message" => $message));
    }
	
?>