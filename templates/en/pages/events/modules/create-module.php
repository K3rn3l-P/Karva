<?php
// only for gm
if (!$IsStaff)
	return;

if (isset($_GET['e'])) {
	$row = $_GET['e'];
	$query = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Events$lang WHERE Row = ?");
	$query->bindParam(1, $row, PDO::PARAM_INT);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_NUM);

	$title = $result[1];
	$content = $result[2];
	$action = 'edit';

	$eventDate = date_create($result[3]);
	$date = date_format($eventDate, 'Y-m-d');
	$hour = date_format($eventDate, 'H');
	$minuteStart = date_format($eventDate, 'i');
	$minuteEnd = $result[4];
} else {
	$row = 0;
	$title = '';
	$content = '';
	$action = 'create';
	$date = date('Y-m-d');
	$hour = 12;
	$minuteStart = 0;
	$minuteEnd = 60;
}
?>

<script src="<?= $AssetUrl ?>js/ckeditor/ckeditor.js"></script>
<script>
	function createEvent() {
		$("#addevent").submit();
	}
</script>
<form id="addevent" action="<?= $TemplateUrl ?>actions/events/<?= $action ?>-event.php" method="post">
	<input type="text" placeholder="Title of Event (Don't make title longer than 250 characters)"
		   value="<?= $title ?>" required="" name="title" id="title">

	<textarea cols="80" id="editor1" name="editor1" rows="10"><?= $content ?></textarea>

	<input type="hidden" value="<?= $row ?>" name="new">

	Date: <input id="date" name="date" type="date" value="<?= $date ?>" style="width:160px;">
	Hours: <input id="hour" name="hour" type="number" min="0" max="23" value="<?= $hour ?>" style="width:60px;">
	Minutes: <input id="minute" name="minute" type="number" min="0" max="59" value="<?= $minuteStart ?>"
					  style="width:60px;">
	Duration Event Minutes: <input id="minuteEnd" name="minuteEnd" type="number" min="0" value="<?= $minuteEnd ?>"
								style="width:60px;">
	<center><input class="nice_button" type="submit" value="<?= $action ?> event"></center>

</form>
</br>
<script>CKEDITOR.replace('editor1');</script>