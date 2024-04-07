<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Support</a> &rarr; <i >My tickets</i></span>
	</div>
    <div class="page-body border_box self_clear">

		<!--BEGIN CONTENT-->
		<div style="text-align:right;">
            <a class="nice_button support-button" href="/?p=support">Create Ticket</a>
            <a class="nice_button support-button nice_active" href="/?p=support&tickets">My Tickets</a>
			<?php if ($IsStaff): ?>
			<a class="nice_button support-button" href="/?p=support&panel">Panel</a>
			<?php endif ?>
		</div>

		<div class="helpContainer">
				<div class="seeTicket"></div>
				<table class="nice_table ticket">
					<tr>
						<td class="sta">Status</td>
						<td class="ogg">Object</td>
						<td class="dat">Last Update</td>
					</tr>
					<?
					$query = $conn->prepare("SELECT * FROM PS_Website.dbo.Users_Ticket WHERE UserUID = ? AND (Status = 1 OR Status = 2) ORDER BY Row DESC");
					$query->bindParam(1, $UserUID, PDO::PARAM_INT);
					$query->execute();
					while ($row = $query->fetch(PDO::FETCH_NUM)) {
						$date = date_create($row[9]);
						$date = date_format($date, 'd/m/y H:i');
						include("modules/ticket-row.php");
					}
					?>            
				</table>
				<br><br>
				<br>
				<p style="margin:0 !important">
					<span style="font-weight:bold;">Status tips:</span>
					<br>
					<img src="<?= $AssetUrl ?>images/support/1-0.png"> Ticket Open | <img
							src="<?= $AssetUrl ?>images/support/1-1.png"> New Message | <img
							src="<?= $AssetUrl ?>images/support/2-0.png"> Ticket Close | <img
							src="<?= $AssetUrl ?>images/support/2-1.png"> New Message and Close</p>
		</div>
		<!--END CONTENT -->

    </div>
</div>