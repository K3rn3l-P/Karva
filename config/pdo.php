<?
	try {
		$conn  = new PDO('sqlsrv:Server=127.0.0.1;Database=PS_UserData', 'sada@', 'asfasfas');
	}
	catch (PDOException $e){
    	die($e->getMessage());
	}

if (session_id() == ""){
    session_start();
}

$uid = 0;
$userid = 'guest';
$status = 0;
$point = 0;
$lang = 'en';

if(isset($_SESSION['UserUID'])){
    
    $uid = $_SESSION['UserUID'];

	$querycheck = $conn->prepare('SELECT Status FROM PS_UserData.dbo.Users_Master WHERE UserUID=?');
	$querycheck->bindValue(1, $uid, PDO::PARAM_INT);
    $querycheck->execute();
	$bannCase = $querycheck->fetch(PDO::FETCH_NUM);
if($bannCase[0] == -5){
	$query = $conn->prepare('SELECT * FROM PS_UserData.dbo.Users_Bann WHERE UserUID=?');
	$query->bindValue(1, $uid, PDO::PARAM_INT);
	$query->execute();
    $bann = $query->fetch(PDO::FETCH_NUM);
    
if ($bann != null) {
if ($bann[4] == 0){
    $ban_date = $bann[3];
    $ban_time = strtotime($ban_date);
    $end_time = $ban_time + 100000;
} else {
    $sban_date = $bann[4];
    $end_time = strtotime($sban_date);
}
    $days_text = $bann[2];
    $ban_reason = $bann[5];
    $date = date("Y-m-d G:i");
	$actual_time = strtotime($date);

if ($end_time < $actual_time){
$querybannRemove=$conn->prepare("DELETE FROM PS_UserData.dbo.Users_Bann WHERE UserUID= ".$uid."");
$querybannRemove->execute();

$querySban=$conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status = '0' WHERE UserUID= ?"); 
$querySban->bindParam(1, $uid, PDO::PARAM_INT);
$querySban->execute();

}
}
} 
    
        $q = $conn->prepare('SELECT UserID, Status, Point, Pw, JoinDate, Email, Lang, VotePoint FROM PS_UserData.dbo.Users_Master WHERE UserUID=?');
		$q->bindParam(1, $uid, PDO::PARAM_INT);
        $q->execute();
		$r = $q->fetch(PDO::FETCH_NUM);

        $userid = $r[0];
        $status = $r[1];
        $point = $r[2];
        $votePoint = $r[7];
        $pw = $r[3];
        $joinDate = $r[4];
        $email = $r[5];
        $lang = $r[6];

}

   $userip = $_SERVER['HTTP_X_FORWARDED_FOR'];

#    $userip = $_SERVER['REMOTE_ADDR'];
#	
#	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
#    $userip = $_SERVER['HTTP_CLIENT_IP'];
#	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
#    $userip = $_SERVER['HTTP_X_FORWARDED_FOR'];
#	} else {
#    $userip = $_SERVER['REMOTE_ADDR'];
#	}	
	
	

function showTop($t, $l) {
    $t = strip_tags($t);
    if (strlen($t) <= $l) {
        return $t;
    }
    $u = strrpos(substr($t, 0, $l), ' ');
    $a = substr($t, 0, $u).' ...';
    return $a;
}
?>

