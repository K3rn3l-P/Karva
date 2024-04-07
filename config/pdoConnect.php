<?
//test whether a player is connected
//@return 1: connected, 0: not connected, -1: does not exist or error
//	function getPlayerStatus($conn, $userUID){
	//define query
//		$query = $conn->prepare('SELECT Leave FROM PS_UserData.dbo.Users_Master WHERE UserUID=?');
	//setup & execute query
//		if ($query == null ||!$query->bindParam(1, $userUID, PDO::PARAM_INT) ||!$query->execute()){
//		return -1;
	//	}
	//get response & verify status (Leave is '1' when user is connected)
//		if (($row = $query->fetch(PDO::FETCH_NUM)) == null){
//		return -1;
//		$status = $row[0];
	//release resource
//		$query->closeCursor();
//		$query = null;
	//return player status
//		return $status;
//		}
//	}

//	Connect to MSSQL server with Shaiya credentials using PDO
	$sqlUser  = getenv('DB_USER');		//	YOUR SHAIYA ACCOUNT NAME
	$sqlPass  = getenv('DB_PASSWORD');	//	YOUR SHAIYA ACCOUNT PASSWORD
	$database = 'PS_UserData';

	try {
		$conn  = new PDO("sqlsrv:Server=127.0.0.1;Database=$database", $sqlUser, $sqlPass);
		$conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
	}
	catch (PDOException $e){
    	die($e->getMessage());
	}	
?>

