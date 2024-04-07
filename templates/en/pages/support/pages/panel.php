<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Support</a> &rarr; <i >Panel</i></span>
	</div>
    <div class="page-body border_box self_clear">

        <!--BEGIN CONTENT-->
		<script src="<?= $AssetUrl ?>js/ckeditor/ckeditor.js"></script>
        <div style="text-align:right;">
            <a class="nice_button support-button" href="/?p=support">Create Ticket</a>
            <a class="nice_button support-button" href="/?p=support&tickets">My Tickets</a>
			<?php if ($IsStaff): ?>
			<a class="nice_button support-button nice_active" href="/?p=support&panel">Panel</a>
			<?php endif ?>
        </div>
		<table class="nice_table ticket">
			<tr>
				<td>ID</td>
				<td class="sta">Category</td>
				<td>Author</td>
				<td class="ogg">Object</td>
				<td class="dat">Updates</td>
			</tr>
			<?
			$query = $conn->prepare("SELECT * FROM PS_Website.dbo.Users_Ticket WHERE (Status = 1 OR Status = 2) ORDER BY Row DESC");
			$query->execute();
			while ($row = $query->fetch(PDO::FETCH_NUM)) {

				$date = date_create($row[9]);
				$date = date_format($date, 'd/m/y H:i');

				$query1 = $conn->prepare("SELECT UserID FROM PS_UserData.dbo.Users_Master WHERE UserUID = ?");
				$query1->bindParam(1, $row[2], PDO::PARAM_INT);
				$query1->execute();
				$row1 = $query1->fetch(PDO::FETCH_NUM);

				switch ($row[4]) {
					case 0:
						$cat = "General";
						break;
					case 1:
						$cat = "Technical";
						break;
					case 2:
						$cat = "Harassment";
						break;
					case 3:
						$cat = "Billing";
						break;
					case 4:
						$cat = "Bug Tracker";
						break;
				}
				include("modules/panel-ticket-row.php");
			}
			?>
		</table>
		<!--END CONTENT -->

    </div>
</div>