<?php

   include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
   
   if (!$UserUID) {
    header("location:/");
    exit;
    }

     $serverURL = "https://s.pinto-lime.ts.net/";
   
   if(isset($_SESSION['paymentStep'])) {
    if( $_SESSION['paymentStep'] != 3) {
		unset($_SESSION['paymentStep']);
      header("location:/");
      exit();
    }
  } else {
      header("location:/");
      exit();
  }


?>



<html>
	<head>
		<title>Donation Received - Shaiya Duff!</title>
	</head>	
	<body>
		<div id="main">
			<h1 style="margin-left: -37%;">PayPal Donation Received!</h1>
			<div id="return">
				<h2>Donation Complete</h2>
				<hr/>
				<h3>We really appreciate your support, thank you very much!</h3>
				<p>Transaction Status -  <span style="color:green">Success</span></p>
				<div class='back_btn'>
					<a href='index.php' id= 'btn'>
						 Go to the website 
					</a>
				</div>
			</div>
		</div>
	</body>
</html>