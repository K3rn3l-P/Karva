<?php
// Includi il file di configurazione
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Assicurati che la connessione al database sia stata stabilita correttamente
if (!$conn) {
    // Logga un messaggio di errore e interrompi l'esecuzione dello script
    error_log("Connessione al database non riuscita in login.php", 0);
    die("Errore di connessione al database. Si prega di riprovare più tardi.");
}

// Recupera le informazioni sull'utente dal database
$q = $conn->prepare('SELECT UserID, Status, Point, email, JoinDate, VotePoint FROM PS_UserData.dbo.Users_Master WHERE UserUID=?');
$q->bindParam(1, $UserUID, PDO::PARAM_INT);
$q->execute();
$ucp = $q->fetch(PDO::FETCH_ASSOC); // Utilizziamo FETCH_ASSOC per ottenere un array associativo

// Determina il tipo di account dell'utente
switch ($ucp['Status']) {
    case 0:
        $type = '<span style="color:#8080ff">PLAYER</span>';
        break;
    case 48:
        $type = '<span style="color:yellow">GAME MASTER</span>';
        break;
    case 32:
        $type = '<span style="color:blue">[A]GAME MASTER</span>';
        break;
    case 16:
        $type = '<span style="color:red">ADMINISTRATOR</span>';
        break;
}

// Determina lo stato dell'account dell'utente
if ($ucp['Status'] < 0) {
    $stat = '<span style="color:red">BANNED</span>';
} else {
    $stat = '<span style="color:green">ACTIVE</span>';
}

// Recupera le informazioni aggiuntive in caso l'utente sia bannato
if ($ucp['Status'] < 0) {
    $q = $conn->prepare('SELECT TOP 1 DaysBann, BanDate, sbandate, Reason FROM PS_UserData.dbo.Users_Bann WHERE UserUID=? ORDER BY RowID DESC');
    $q->bindParam(1, $UserUID, PDO::PARAM_INT);
    $q->execute();
    $ban = $q->fetch(PDO::FETCH_ASSOC);
}

// Alcuni dei seguenti valori potrebbero essere vuoti se l'utente non è bannato
$banReason = isset($ban['Reason']) ? $ban['Reason'] : '';
$banDate = isset($ban['DaysBann']) && isset($ban['BanDate']) && isset($ban['sbandate']) ? $ban['DaysBann'] . ' ' . $ban['BanDate'] . ' > ' . $ban['sbandate'] : '';

?>
<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>User Panel</a> </span>
    </div>
    <div class="page-body border_box self_clear">

        <div class="ucp_ui border_box">
            <div class="ucp_ui-inner self_clear">
                <div class="ui_col col_2 border_box" style="width:345px">
                    <div class="info-table">
                        <!-- Informazioni sull'utente -->
                        <div class="info-row row-1">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/user.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">UserName</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= $ucp['UserID'] ?></div>
                        </div>
                        <div class="info-row row-3">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/plugin.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">Email</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= $ucp['email'] ?></div>
                        </div>
                        <div class="info-row row-3">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/shield.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">Account status</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= $type ?></div>
                        </div>
                        <div class="info-row row-3">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/shield.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">Ban status</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= $stat ?></div>
                        </div>
                        <div class="info-row row-4">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/date.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">Member since</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= date_format(date_create($ucp['JoinDate']), 'Y-m-d') ?></div>
                        </div>
                    </div>
                </div>
                <!-- Aggiunta la seconda colonna -->
                <div class="ui_col col_2 border_box" style="width:345px">
                    <!-- Informazioni aggiuntive -->
                    <div class="info-table">
                        <div class="info-row row-3">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/world.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">Shaiya Points</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= $ucp['Point'] ?></div>
                        </div>
                        <div class="info-row row-3">
                            <div class="info-col col-1 overflow_ellipsis"><img alt="" src="<?= $AssetUrl ?>images/icons/world.png" width="" height=""></div>
                            <div class="info-col col-2 overflow_ellipsis">Vote Points</div>
                            <div class="info-col col-3 overflow_ellipsis"><?= $ucp['VotePoint'] ?></div>
                        </div>

						<?php
                        // Verifica se l'utente è bannato
                        if ($ucp['Status'] < 0) {
                            // Se lo status è negativo, l'utente è bannato
                            $banReason = "BANNED";
                            // Determina il motivo e la durata del ban
                            switch ($ucp['Status']) {
                                case -5:
                                    $banReason .= " (Permanent)";
                                    break;
                                // Aggiungi altri casi se necessario per gestire altri tipi di ban
                                default:
                                    $banReason .= " (Temporary)";
                                    break;
                            }
                            // Mostra lo stato del ban e il motivo
                            echo '<div class="info-row row-3">
                                <div class="info-col col-1 overflow_ellipsis" style="width: 20px;"><img alt="" src="' . $AssetUrl . 'images/icons/shield.png" width="" height=""></div>
                                <div class="info-col col-2 overflow_ellipsis" style="width: 60px;">Ban Reason:</div>
                                <div class="info-col col-3 overflow_ellipsis" style="width: 230px;color:red" title="' . $banReason . '">' . $banReason . '</div>
                            </div>';
                        } else {
                            // Se lo status non è negativo, l'utente non è bannato
                            // Puoi aggiungere qui altre azioni da eseguire nel caso in cui l'utente non sia bannato
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="ucp_btns">
            <div class="ucp_btns-inner self_clear">
                <!-- Aggiungi qui i pulsanti per le azioni dell'utente -->
                <div class="ucp_btn border_box" title="VOTE - FREE SHAIYA POINTS">
                    <a href="/?p=vote" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-9.png" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">VOTE<i> - FREE SHAIYA POINTS</i></div>
                    </a>
                </div>
                <div class="ucp_btn border_box" title="DONATE - BUY SHAIYA POINTS">
                    <a href="/?p=billing" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-2.jpg" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">DONATE<i> - BUY SHAIYA POINTS</i></div>
                    </a>
                </div>                
                
                <div class="ucp_btn border_box" title="ITEM MALL - CHECK OUR STORE">
                    <a href="/?p=itemmall" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-7.png" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">Item Mall<i> - Check our store</i></div>
                    </a>
                </div>                
                
                <div class="ucp_btn border_box" title="SPEND SHAIYA POINTS AND GET REWARDED">
                    <a href="/?p=tiered" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-8.png" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">Tiered Spender<i> - Loyalty Rewards</i></div>
                    </a>
                </div>                                
                
                <div class="ucp_btn border_box" title="PVP REWARDS & GRB REWARDS">
                    <a href="/?p=pvp-reward" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-10.png" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">PvP Rewards<i> & GRB Rewards</i></div>
                    </a>
                </div>                            
                
                <div class="ucp_btn border_box" title="GIFT CODE - INSERT CODE">
                    <a href="/?p=gift-code" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/gift2.png" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">Gift Code<i> - Insert Code</i></div>
                    </a>
                </div>
                                                                                        
                <div class="ucp_btn border_box" title="SETTINGS - CHANGE PASSWORD">
                    <a href="/?p=settings" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-4.jpg" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">SETTINGS<i> - Change Password</i></div>
                    </a>
                </div>                
                
                <div class="ucp_btn border_box" title="SUPPORT - MY TICKETS">
                    <a href="/?p=support&tickets" direct="0" data-hasevent="1">
                        <div class="ucp_btn-icon border_box">
                            <img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-5.png" width="" height="">
                        </div>
                        <div class="ucp_btn-text border_box">SUPPORT<i> - MY TICKETS</i></div>
                    </a>
                </div>
            </div>
        </div>
