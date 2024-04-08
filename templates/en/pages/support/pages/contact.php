<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Support</a> &rarr; <i >Contact</i></span>
	</div>
    <div class="page-body border_box self_clear">

		<!--BEGIN CONTENT-->
		<script src="<?= $AssetUrl ?>js/ckeditor/ckeditor.js"></script>
		<script>
			function checkTitle() {
				var title = $('#oggetto').val();
				if (title.length > 4 && title.length < 21) {
					$('#oggetto').css('border-color', 'green');
				} else {
					$('#oggetto').css('border-color', 'red');
				}
			}

			function submitTicket() {
				var title = $('#oggetto').val();
				if (title.length > 4 && title.length < 21) {
					$("#send").submit();
				} else {
					$('#oggetto').css('border-color', 'red');
				}
			}
		</script>
		<?php
		$opt = isset($_GET['opt']) ? $_GET['opt'] : 0;
		?>
		<div style="text-align:right;">
            <a class="nice_button support-button nice_active" href="/?p=support">Create Ticket</a>
            <a class="nice_button support-button" href="/?p=support&tickets">My Tickets</a>
			<?php if ($IsStaff): ?>
			<a class="nice_button support-button" href="/?p=support&panel">Panel</a>
			<?php endif ?>
		</div>

		<div class="helpContainer">
			<form action="<?= $TemplateUrl ?>actions/support/contact.php" method="post" id="send">
				<label class="optTitle">Category:</label>
				<select id="opzione" name="opzione" style="width:300px;">
					<option value="0" <? if ($opt == 0) {
						echo 'selected';
					} ?>>General
					</option>
					<option value="1" <? if ($opt == 1) {
						echo 'selected';
					} ?>>Technical Help
					</option>
					<option value="2" <? if ($opt == 2) {
						echo 'selected';
					} ?>>Harassment
					</option>
					<option value="3" <? if ($opt == 3) {
						echo 'selected';
					} ?>>Billing Help
					</option>
					<option value="4" <? if ($opt == 4) {
						echo 'selected';
					} ?>>Bug Tracker
					</option>
				</select>
				<br>
				<br>
				<label class="optTitle">Title: &nbsp; &nbsp; &nbsp; &nbsp;</label>
				<input type="text" id="oggetto" name="oggetto" value="" placeholder="Title" style="width:300px;"
					   onkeyup="checkTitle()">
				<br>
				<br>
				<label class="optTitle">Message:</label>

				<textarea cols="80" id="editor1" name="editor1" rows="10" style="width:600px;"></textarea>
				<input type="hidden" value="1" name="new" id="new">
			</form>
			<input type="submit" value="Submit" class="form-submit" onclick="submitTicket()" style="margin: 10px auto; display: block; width: 150px;">
		</div>

		<script>CKEDITOR.replace('editor1');</script>
		<!--END CONTENT -->

    </div>
</div>