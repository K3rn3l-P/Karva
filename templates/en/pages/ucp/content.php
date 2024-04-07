<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>User Panel</a> </span>
	</div>
    <div class="page-body border_box self_clear">

		<div class="ucp_ui border_box">
			<div class="ucp_ui-inner self_clear">
				<?
				$q = $conn->prepare('SELECT UserID, Status, Point, email, RegDate, VotePoint FROM PS_UserData.dbo.Users_Master WHERE UserUID=?');
				$q->bindParam(1, $UserUID, PDO::PARAM_INT);
				$q->execute();
				$ucp = $q->fetch(PDO::FETCH_NUM);
				switch ($ucp[2]) {
					case 0:
						$type = '<span style="color:#8080ff">PLAYER</span>';
						break;
					case 1:
						$type = '<span style="color:yellow">GAME SAGE</span>';
						break;
					case 2:
						$type = '<span style="color:blue">GAME MASTER</span>';
						break;
					case 3:
						$type = '<span style="color:red">ADMINISTRATOR</span>';
						break;
				}
				if ($ucp[1] < 0) {
					$stat = '<span style="color:red">BANNED</span>';
				} else {
					$stat = '<span style="color:green">ACTIVE</span>';
				}
				?>
				<div class="ui_col col_2 border_box" style="width:345px">
					<div class="info-table">
						<div class="info-row row-1">
							<div class="info-col col-1 overflow_ellipsis"><img alt=""
																			   src="<?= $AssetUrl ?>images/icons/user.png"
																			   width="" height=""></div>
							<div class="info-col col-2 overflow_ellipsis">UserName</div>
							<div class="info-col col-3 overflow_ellipsis"><?= $UserID ?></div>
						</div>
						<div class="info-row row-2">
							<div class="info-col col-1 overflow_ellipsis"><img alt=""
																			   src="<?= $AssetUrl ?>images/icons/world.png"
																			   width="" height=""></div>
							<div class="info-col col-2 overflow_ellipsis">IP address</div>
							<div class="info-col col-3 overflow_ellipsis"><?= $UserIP ?></div>
						</div>
						<div class="info-row row-3">
							<div class="info-col col-1 overflow_ellipsis"><img alt=""
																			   src="<?= $AssetUrl ?>images/icons/plugin.png"
																			   width="" height=""></div>
							<div class="info-col col-2 overflow_ellipsis">Email</div>
							<div class="info-col col-3 overflow_ellipsis"><?= $UserInfo["Email"] ?></div>
						</div>
					</div>
				</div>
				<div class="ui_col col_3 border_box" style="width:345px">
					<div class="info-table">
						<div class="info-row row-2">
							<div class="info-col col-1 overflow_ellipsis"><img alt=""
																			   src="<?= $AssetUrl ?>images/icons/coins.png"
																			   width="" height=""></div>
							<div class="info-col col-2 overflow_ellipsis">Shaiya Points</div>
							<div class="info-col col-3 overflow_ellipsis"><?= $Point ?></div>
							<div class="info-col col-4 overflow_ellipsis"></div>
						</div>
						
						<div class="info-row row-3">
							<div class="info-col col-1 overflow_ellipsis"><img alt=""
																			   src="<?= $AssetUrl ?>images/icons/shield.png"
																			   width="" height=""></div>
							<div class="info-col col-2 overflow_ellipsis">Account status</div>
							<div class="info-col col-3 overflow_ellipsis"><? echo $stat; ?> </div>
							<div class="info-col col-4 overflow_ellipsis"></div>
						</div>
						<?
						if ($ucp[1] < 0) {

							$q = $conn->prepare('SELECT TOP 1 DaysBann, BanDate, sbandate, Reason FROM PS_UserData.dbo.Users_Bann WHERE UserUID=? ORDER BY RowID DESC');
							$q->bindParam(1, $UserUID, PDO::PARAM_INT);
							$q->execute();
							$ban = $q->fetch(PDO::FETCH_NUM);

							echo '<div class="info-row row-3">
									<div class="info-col col-1 overflow_ellipsis" style="width: 20px;"><img alt="" src="' . $AssetUrl . 'images/icons/shield.png" width="" height=""></div>
									<div class="info-col col-2 overflow_ellipsis" style="width: 60px;">Ban Reason:</div>
									<div class="info-col col-3 overflow_ellipsis" style="width: 230px;color:red" title="' . $ban[3] . '">' . $ban[3] . '</div>
								</div>';
							if ($ban[0] == 'Permanent') {
								$date = $ban[0];
							} else {
								$date = $ban[0] . ' ' . $ban[1] . ' > ' . $ban[2];
							}
							echo '<div class="info-row row-3">
										<div class="info-col col-1 overflow_ellipsis" style="width: 20px;"><img alt="" src="' . $AssetUrl . 'images/icons/shield.png" width="" height=""></div>
										<div class="info-col col-2 overflow_ellipsis" style="width: 60px;">Period Bann:</div>
										<div class="info-col col-3 overflow_ellipsis" style="width: 230px;color:red" title="' . $date . '">' . $date . '</div>
									</div>';
						}
						?>

						<div class="info-row row-4">
							<div class="info-col col-1 overflow_ellipsis"><img alt=""
																			   src="<?= $AssetUrl ?>images/icons/date.png"
																			   width="" height=""></div>
							<div class="info-col col-2 overflow_ellipsis">Member since</div>
							<div class="info-col col-3 overflow_ellipsis"><? echo date_format(date_create($ucp[4]), 'Y-m-d'); ?> </div>
							<div class="info-col col-4 overflow_ellipsis"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ucp_btns">
			<div class="ucp_btns-inner self_clear">
			
			
				<div class="ucp_btn   border_box" title="VOTE - FREE SHAIYA POINTS">
					<a href="/?p=vote" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-9.png" width="" height=""></div>
						<div class="ucp_btn-text border_box">VOTE<i> - FREE SHAIYA POINTS</i></div>
					</a>
				</div>
				<div class="ucp_btn   border_box" title="DONATE - BUY SHAIYA POINTS">
					<a href="/?p=billing" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-2.jpg" width="" height=""></div>
						<div class="ucp_btn-text border_box">DONATE<i> - BUY SHAIYA POINTS</i></div>
					</a>
				</div>				
				
				<div class="ucp_btn   border_box" title="ITEM MALL - CHECK OUR STORE">
					<a href="/?p=itemmall" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-7.png" width="" height=""></div>
						<div class="ucp_btn-text border_box">Item Mall<i> - Check our store</i></div>
					</a>
				</div>				
				
				<div class="ucp_btn   border_box" title="SPEND SHAIYA POINTS AND GET REWARDED">
					<a href="/?p=tiered" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-8.png" width="" height=""></div>
						<div class="ucp_btn-text border_box">Tiered Spender<i> - Loyalty Rewards</i></div>
					</a>
				</div>								
				
				<div class="ucp_btn   border_box" title="PVP REWARDS & GRB REWARDS">
					<a href="/?p=pvp-reward" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-10.png" width="" height=""></div>
						<div class="ucp_btn-text border_box">PvP Rewards<i> & GRB Rewards</i></div>
					</a>
				</div>							
				
				<div class="ucp_btn   border_box" title="GIFT CODE - INSERT CODE">
					<a href="/?p=gift-code" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/gift2.png" width="" height=""></div>
						<div class="ucp_btn-text border_box">Gift Code<i> - Insert Code</i></div>
					</a>
				</div>
																							
				<div class="ucp_btn   border_box" title="SETTINGS - CHANGE PASSWORD">
					<a href="/?p=settings" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-4.jpg" width="" height=""></div>
						<div class="ucp_btn-text border_box">SETTINGS<i> - Change Password</i></div>
					</a>
				</div>				
				
				<div class="ucp_btn   border_box" title="SUPPORT - MY TICKETS">
					<a href="/?p=support&tickets" direct="0" data-hasevent="1">
						<div class="ucp_btn-icon border_box">
						<img alt="" src="<?= $HomeUrl ?>images/page-ucp/icon-5.png" width="" height=""></div>
						<div class="ucp_btn-text border_box">SUPPORT<i> - MY TICKETS</i></div>
					</a>
				</div>
							
				
				
			</div>
		</div>

	
	</div>
</div>