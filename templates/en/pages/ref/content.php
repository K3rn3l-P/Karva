<?php

// Requisiti per il progresso del riferimento
$reqProgress = ["1" => 1, "2" => 1000];
// Ricompense per ogni passo
$stepRewards = ["1" => 50, "2" => 50];

// Conteggio dei riferimenti
$refCount = 0;

// Query per ottenere i riferimenti dell'utente corrente
$stmt = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.Ref WHERE UserUID = :user_uid AND Del = 0");
$stmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
$stmt->execute();

// Ciclo attraverso i riferimenti
while ($ref = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $refUID = $ref["RefUID"];

    // Controllo delle restrizioni per IP comune
    $checkStmt = $pdo->prepare("SELECT 1 FROM PS_UserData.dbo.UserLoginLog 
                                WHERE (UserUID = :ref_uid AND UserIP IN (SELECT UserIP FROM PS_UserData.dbo.UserLoginLog WHERE UserUID = :user_uid))
                                OR (UserUID = :user_uid AND UserIP IN (SELECT UserIP FROM PS_UserData.dbo.UserLoginLog WHERE UserUID = :ref_uid))");
    $checkStmt->bindParam(':ref_uid', $refUID, PDO::PARAM_INT);
    $checkStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
    $checkStmt->execute();

    // Rimuovere il riferimento se il controllo non è riuscito
    if ($checkStmt->rowCount() > 0) {
        $delStmt = $pdo->prepare("UPDATE PS_WebSite.dbo.Ref SET Del = 1 WHERE UserUID = :user_uid AND RefUID = :ref_uid");
        $delStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
        $delStmt->bindParam(':ref_uid', $refUID, PDO::PARAM_INT);
        $delStmt->execute();
    } else {
        $refCount++;

        // Ottenere il progresso del riferimento
        $progressStmt = $pdo->prepare("SELECT COUNT(1) AS Progress1, SUM(K1) AS Progress2 FROM PS_GameData.dbo.Chars WHERE UserUID = :ref_uid");
        $progressStmt->bindParam(':ref_uid', $refUID, PDO::PARAM_INT);
        $progressStmt->execute();
        $refProgress = $progressStmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se il riferimento ha soddisfatto i requisiti
        if ($refProgress["Progress1"] < 1 || $refProgress["Progress2"] < 1000) {
            continue;
        }

        // Calcola le ricompense per ogni passo completato
        for ($i = 1; $i <= 2; $i++) {
            $doneCount = floor(($refProgress["Progress$i"] - $ref["Progress$i"]) / $reqProgress[$i]);
            if (!$doneCount) {
                continue;
            }
            
            // Calcola il nuovo progresso del riferimento
            $newProgress = $ref["Progress$i"] + $reqProgress[$i] * $doneCount;

            // Aggiorna il nuovo progresso del riferimento nel database
            $updateStmt = $pdo->prepare("UPDATE PS_WebSite.dbo.Ref SET Progress$i = :new_progress WHERE UserUID = :user_uid AND RefUID = :ref_uid");
            $updateStmt->bindParam(':new_progress', $newProgress, PDO::PARAM_INT);
            $updateStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
            $updateStmt->bindParam(':ref_uid', $refUID, PDO::PARAM_INT);
            $updateStmt->execute();

            // Calcola e aggiorna le ricompense SP per l'utente
            $reward = $stepRewards[$i] * $doneCount;
            $updateRewardStmt = $pdo->prepare("UPDATE PS_WebSite.dbo.RefMoney SET CurrentMoney = CurrentMoney + :reward, TotalGained = :reward, Count$i = Count$i + :done_count WHERE UserUID = :user_uid");
            $updateRewardStmt->bindParam(':reward', $reward, PDO::PARAM_INT);
            $updateRewardStmt->bindParam(':done_count', $doneCount, PDO::PARAM_INT);
            $updateRewardStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
            $updateRewardStmt->execute();

            // Aggiorna i punti SP dell'utente
            $updatePointStmt = $pdo->prepare("UPDATE PS_UserData.dbo.Users_Master SET Point = Point + :reward WHERE UserUID = :user_uid");
            $updatePointStmt->bindParam(':reward', $reward, PDO::PARAM_INT);
            $updatePointStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
            $updatePointStmt->execute();
        }
    }
}

// Verifica e inserimento del record di ricompensa per l'utente nel caso non esista
$userProgressStmt = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.RefMoney WHERE UserUID = :user_uid");
$userProgressStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
$userProgressStmt->execute();

if ($userProgressStmt->rowCount() == 0) {
    $insertProgressStmt = $pdo->prepare("INSERT INTO PS_WebSite.dbo.RefMoney (UserUID) VALUES (:user_uid)");
    $insertProgressStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
    $insertProgressStmt->execute();

    $userProgressStmt = $pdo->prepare("SELECT * FROM PS_WebSite.dbo.RefMoney WHERE UserUID = :user_uid");
    $userProgressStmt->bindParam(':user_uid', $UserUID, PDO::PARAM_INT);
    $userProgressStmt->execute();
}

$userProgress = $userProgressStmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="page" id="ref-page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Rewards</a> &rarr; <i >Referrer System</i></span>
	</div>
    <div class="page-body border_box self_clear">

		<div class="panel-body no-padding-vertical">
			<div class="row first-block content-text">
				<p>Invite your friends to this game using the referral system. You and your friends will not only enjoy playing together, but also receive great gifts!</p>
				<div class="row">
					<div class="col-md-4">
						<div class="step">
							<span class="step__num">1</span>Send your friends a direct link to our site from the form below.
						</div>
					</div>
					<div class="col-md-4">
						<div class="step">
							<span class="step__num">2</span>Get <?= $currencyCode ?> for the progress of invited friends.
						</div>
					</div>
					<div class="col-md-4">
						<div class="step">
							<span class="step__num">3</span>Claim your free <?= $currencyCode ?> !
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel-heading">
			<h4 class="text-center">Personal rewards for invited friends</h4>
		</div>
		<div class="content-bg">
			<div class="content-text">
				<center><p>Get SP for the game progress of your friends! The more active your friends are, the more SP you earn. Only those friends who have registered using your referral link are counted.</p>
				</center>
			</div>
		</div>

		<div class="panel-body">
			<div class="row">

				<div class="col-md-4 col-md-offset-2">
					<div class="quest-item">
						<div class="quest-content">
							<h1><?= $stepRewards[1] ?> <?= $currencyCode ?></h1>
							<h2>
								<span>For every 70 level up of your friend</span>
							</h2>
						</div>
						<div class="quest-bottom">
							<span>Received times: <?= $userProgress["Count1"] ?></span>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="quest-item">
						<div class="quest-content">
							<h1><?= $stepRewards[2] ?> <?= $currencyCode ?></h1>
							<h2>
								<span>For every 1 000 kills of your friend</span>
							</h2>
						</div>
						<div class="quest-bottom">
							<span>Received times: <?= $userProgress["Count2"] ?></span>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="panel-heading">
			<h4 class="text-center">Copy link to your friend</h4>
		</div>
		<div class="panel-body text-center ">
			<p>Use this link to invite friends to the game</p>
			<input type="text" class="form-control text-center" readonly value="https://s.pinto-lime.ts.net//?ref=<?= $UserUID ?>" />
			
			<p data-tip="Your friend can insert this id to registration form field">Your Referrer ID is:</p>
			<input data-tip="Your friend can insert this id to registration form field" type="text" class="form-control text-center" readonly value="<?= $UserUID ?>" />
		</div>

		<div class="panel-heading">
			<h4 class="text-center">My referrals: <?= $refCount ?></h4>
		</div>
		
		<div class="panel-body">
			<div class="content-text">
				<table class='nice_table'>
					<tr>
						<td class="center"></td>
						<td class="center">Nickname</td>
						<td class="center">Kills</td>
						<td class="center">Earned <?= $currencyCode ?></td>
						<td class="center">Register date</td>
					</tr>
					<?php
						$query = "SELECT R.*, M.Progress1, M.Progress2, M.Progress3, C.*, U.JoinDate AS [JDate] 
									FROM PS_WebSite.dbo.RefMoney AS R
									LEFT JOIN PS_WebSite.dbo.Ref AS M ON R.UserUID=M.RefUID
									LEFT JOIN PS_UserData.dbo.Users_Master AS U ON R.UserUID=U.UserUID		
									OUTER APPLY (SELECT TOP 1 * FROM PS_GameData.dbo.Chars C WHERE C.UserUID=R.UserUID ORDER BY K1 DESC) C
									WHERE R.UserUID in (SELECT RefUID FROM PS_WebSite.dbo.Ref WHERE UserUID=$UserUID) AND M.Del=0
									ORDER BY C.K1 DESC";
						
						$stmt = $conn->prepare($query);
						$stmt->execute();
						$row = 1;
						while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
							// Посчитать количество монет, полученных этим рефералом
							$reward = 0;
							for ($i = 1; $i <= 2; $i++) {
								$doneCount = floor($item["Progress$i"] / $reqProgress[$i]); // Количество выполненных условий рефералом
								$reward += $stepRewards[$i] * $doneCount;
							}
							include("modules/ref-row.php");
							$row++;
						}
					?>
				</table>
			</div>
			
		</div>
		
		<div class="alert alert-warning">
			<p>Please note that the referral can be deleted if you or your referral logged in to someone else's account!</p>
		</div>
		
	</div>
</div>

<style>

/**
 * COMEBACK
 */
 
.border_box div {
    box-sizing: border-box;
}
.panel-heading {
	clear: both;
	text-transform: uppercase;
	background-color: #1f2021;
}

#ref-page .first-block {
    background: url("/images/comeback/header.jpg") no-repeat center 0;
    background-size: cover;
}
#vote-page .first-block {
    background: url("/images/comeback/bg-2.jpg") no-repeat center;
    background-size: cover;
}
.step {
    position: relative;
    padding: 0 0 0 40px;
    margin: 30px 0 15px
}
.step__num {
    position: absolute;
    left: 0;
    top: 0;
    width: 28px;
    height: 28px;
    border: 2px solid #d0c696;
    border-radius: 100%;
    font: 15px/24px HelveticaNeue;
    text-align: center;
    color: #d0c696
}
.content-text {
    color: #dcdcdc;
    padding: 20px 26px;
}

.quest-item, .ref-item {
    background-color: rgba(10,10,12,0.5);
    -webkit-transition-timing-function: ease,linear,ease;
    -moz-transition-timing-function: ease,linear,ease;
    transition-timing-function: ease;
    -webkit-transition-duration: .4s,.4s,.4s;
    -moz-transition-duration: .4s,.4s,.4s;
    transition-duration: .4s,.4s,.4s;
    border: 1px solid #927345;
    /*box-shadow: 0 0 11px 7px rgba(0,0,0,.2);*/
    cursor: default;
    overflow: hidden;
    z-index: 1;
}
.quest-item:hover, .ref-item:hover {
    -webkit-transition-timing-function: ease,step-start,ease;
    -moz-transition-timing-function: ease,step-start,ease;
    transition-timing-function: ease,step-start,ease;
    -webkit-transform: scale(1.05);
    -moz-transform: scale(1.05);
    -ms-transform: scale(1.05);
    -o-transform: scale(1.05);
    transform: scale(1.05);
}
.quest-content, .item-content {
    padding: 10px 5px 10px;
    text-align: center;
    transition: .4s;
    min-height: 130px;
}
.quest-content h1, .ref-item h1 {
    color: #937341;
    font-size: 14px;
}
.quest-content h2, .ref-item h2 {
    color: #c4b998;
    font-size: 11px;
    letter-spacing: .05em;
    padding-top: 6px;
}
.quest-content h1, .quest-content h2, .ref-item h1, .ref-item h2 {
    text-transform: uppercase;
}
.quest-bottom, .item-action {
    font-family: Beaufort for LOL,Arial,sans-serif;
    font-weight: 700;
    border-top: 1px solid #927345;
    color: #937341;
    font-size: 11px;
    letter-spacing: .3em;
    line-height: 50px;
    text-align: center;
    text-transform: uppercase;
    transition: .4s;
}
.disabled .item-action {
    cursor: not-allowed;
    visibility: collapse;
}

.ref-item {
    position: relative;
    height: 230px;
    margin-bottom: 20px;
}
.item-height {
    max-height: 150px;
}
.item-action {
    transform: translateY(100%);
    cursor: pointer;
}
.ref-item:hover .item-action {
    transform: translateY(0);
}
.item-action:hover {
    background-color: rgba(27, 27, 29, 0.3);
    color: #b59447;
}

.faq {
    padding: 5px 0 4px 35px;
    margin: 25px 0;
    line-height: 1.3em;
    background: url("/images/sun.png") no-repeat 0 0
}
.faq__title {
    margin: 0 0 10px
}

.alert {
	
    padding: 1px;
 

}
.alert a,
.alert .alert-link {
  color: #fff;
}

.alert-success {
  color: #ebebeb;
  background-color: #5cb85c;
  border-color: transparent;
}
.alert-success hr {
  border-top-color: rgba(0, 0, 0, 0);
}
.alert-success .alert-link {
  color: #d2d2d2;
}
.alert-info {
  color: #ebebeb;
  background-color: #5bc0de;
  border-color: transparent;
}
.alert-info hr {
  border-top-color: rgba(0, 0, 0, 0);
}
.alert-info .alert-link {
  color: #d2d2d2;
}
.alert-warning {
  color: #ad1919;
  background-color: #ffffff;
  border-color: transparent;
}
.alert-warning hr {
  border-top-color: rgba(0, 0, 0, 0);
}
.alert-warning .alert-link {
  color: #d2d2d2;
}
.alert-danger {
  color: #ebebeb;
  background-color: #d9534f;
  border-color: transparent;
}
.alert-danger hr {
  border-top-color: rgba(0, 0, 0, 0);
}
.alert-danger .alert-link {
  color: #d2d2d2;
}
.clearfix:before, .clearfix:after, .dl-horizontal dd:before, .dl-horizontal dd:after, .container:before, .container:after, .container-fluid:before, .container-fluid:after, .row:before, .row:after, .form-horizontal .form-group:before, .form-horizontal .form-group:after, .btn-toolbar:before, .btn-toolbar:after, .btn-group-vertical > .btn-group:before, .btn-group-vertical > .btn-group:after, .nav:before, .nav:after, .navbar:before, .navbar:after, .navbar-header:before, .navbar-header:after, .navbar-collapse:before, .navbar-collapse:after, .pager:before, .pager:after, .panel-body:before, .panel-body:after, .modal-header:before, .modal-header:after, .modal-footer:before, .modal-footer:after {
    display: table;
    content: " ";
}
.clearfix:after, .dl-horizontal dd:after, .container:after, .container-fluid:after, .row:after, .form-horizontal .form-group:after, .btn-toolbar:after, .btn-group-vertical > .btn-group:after, .nav:after, .navbar:after, .navbar-header:after, .navbar-collapse:after, .pager:after, .panel-body:after, .modal-header:after, .modal-footer:after {
    clear: both;
}
</style>