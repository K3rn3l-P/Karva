<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Support</a> &rarr; <i >Ticket answer</i></span>
	</div>
    <div class="page-body border_box self_clear">

        <!--BEGIN CONTENT-->
		<script src="<?= $AssetUrl ?>js/ckeditor/ckeditor.js"></script>
        <div style="text-align:right;">
            <a class="nice_button support-button" href="/?p=support">Create Ticket</a>
            <a class="nice_button support-button" href="/?p=support&tickets">My Tickets</a>
			<?php if ($IsStaff): ?>
			<a class="nice_button support-button" href="/?p=support&panel">Panel</a>
			<?php endif ?>
        </div>
		<?php
		$n = 1;
		$ticketID = $_GET['panel'];

		$query = $conn->prepare("SELECT * FROM PS_Website.dbo.Users_Ticket WHERE TicketID = ? ORDER BY Row ASC");
		$query->bindParam(1, $ticketID, PDO::PARAM_INT);
		$query->execute();
		$status = 0;
		while ($row = $query->fetch(PDO::FETCH_NUM)) {
			if ($n == 1) {
				echo '<div class="titleTicket">' . $row[5] . '</div>';
			}

			$date = date_create($row[9]);
			$date = date_format($date, 'd/m/y H:i');

			if ($row[3] == NULL) {
				$query1 = $conn->prepare("SELECT UserID FROM PS_UserData.dbo.Users_Master WHERE UserUID = ?");
				$query1->bindParam(1, $row[2], PDO::PARAM_INT);
				$query1->execute();
				$row1 = $query1->fetch(PDO::FETCH_NUM);
				echo '<div class="box ">
			<div class="date">' . $date . '</div>
			<div class="user question">' . $row1[0] . '</div>
			' . $row[6] . '
			</div>';
			} else {
				$query1 = $conn->prepare("SELECT UserID FROM PS_UserData.dbo.Users_Master WHERE UserUID = ?");
				$query1->bindParam(1, $row[3], PDO::PARAM_INT);
				$query1->execute();
				$row1 = $query1->fetch(PDO::FETCH_NUM);
				echo '<div class="box">
						<div class="date">' . $date . '</div>
						<div class="user answer">' . $row1[0] . '</div>
						' . $row[6] . '
					</div>';
			}
			$n++;
			$status = $row[7];
		}
		if ($status == 1) {
			echo "<form action='$TemplateUrl/actions/support/answer.php' method='post' >
					<textarea cols='80' id='editor1' name='editor1' rows='10'></textarea>
					<input value='$ticketID' type='hidden' name='TicketID'>
					<select name='ticketStatus'>
						<option value='1' selected>Active</option>
						<option value='2'>Close</option>
					</select>
					<input type='submit' value='Forward' class='form-submit' style='margin: 10px auto; display: block; width: 150px;'>
				</form>";
		} elseif ($status == 2) {
			echo "<div class='closed'>Ticket Close</div>			
				<form action='$TemplateUrl/actions/support/answer.php' method='post' >
					<input value='$ticketID' type='hidden' name='TicketID'>
					<input value='yes' type='hidden' name='reopen'>
					<input type='submit' value='Reopen ticket' class='form-submit' style='margin: 10px auto; display: block; width: 150px;'>
				</form>";
		}
		?>
		<script>CKEDITOR.replace('editor1');</script>
		<!--END CONTENT -->

    </div>
</div>