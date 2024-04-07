<?php
$userId = isset($_GET["username"]) ? $_GET["username"] : "";
$charName = isset($_GET["charname"]) ? $_GET["charname"] : "";
$actionTypes = isset($_GET["action-type"]) ? $_GET["action-type"] : array();
$rows = isset($_GET["rows"]) && $_GET["rows"] > 0 ? $_GET["rows"] : 100;
//$charName = isset($_GET["charname"]) ? $_GET["charname"] : ""; 
$dateStart = isset($_GET["date-start-enabled"], $_GET["date-start"]) ? $_GET["date-start"] : "";
$dateEnd = isset($_GET["date-end-enabled"], $_GET["date-end"]) ? $_GET["date-end"] : "";

// User condition
$userCondition = "1=1";

if ($userId) {
	$stmt = $conn->prepare("SELECT UserUID FROM PS_UserData.dbo.Users_Master WHERE UserID=?");
	$stmt->bindValue(1, $userId, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);						
	$userUid = $row ? $row['UserUID'] : 0;	
	$userCondition = "AL.UserUID=$userUid";
} 
if ($charName) {
	$stmt = $conn->prepare("SELECT CharID FROM PS_GameData.dbo.Chars WHERE CharName=?");
	$stmt->bindValue(1, $charName, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);						
	$charId = $row ? $row['CharID'] : 0;
	$userCondition = "CharID=$charId";
}

// Action Type condition
$actionCondition = "1=1";
if ($actionTypes) {
	$label = implode(",", $actionTypes);
	$actionCondition = "ActionType in ($label)";
}

// Date condition
$dateCondition = "1=1";
if ($dateStart) {
	$dateStart = (new DateTime($dateStart))->format("Y-m-d H:i:s");
	$dateCondition = "ActionTime >= '$dateStart'";
	//$dateStart = date("Y-m-d h:i", strtotime($dateStart));
	if ($dateEnd) {
		$dateEnd = (new DateTime($dateEnd))->format("Y-m-d H:i:s");
		$dateCondition = "ActionTime BETWEEN '$dateStart' AND '$dateEnd'";
	}
} else if ($dateEnd) {
	$dateEnd = (new DateTime($dateEnd))->format("Y-m-d H:i:s");
	$dateCondition = "ActionTime <= '$dateEnd'";
}

// Join all conditions
$conditionArray = array($userCondition, $actionCondition, $dateCondition);
$conditions = "WHERE " . implode(" AND ", $conditionArray);

// Query
$query = "SELECT TOP $rows AL.*, UM.AdminLevel FROM PS_GameLog.dbo.ActionLog AL
LEFT JOIN PS_UserData.dbo.Users_Master UM ON UM.UserUID=AL.UserUID
$conditions	ORDER BY [row] DESC";

// Execute
$stmt = $conn->prepare($query);
$stmt->execute();
?>
<div class="page" id="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a href="/?p=gm-panel-n3w">GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear" style="padding: 5px 25px;">
	
		<div class="node format">
			<div class="container">
				<div class="search-container">
			  
					<form class="beauty-form" id="search-form">					
						<input type="hidden" name="p" value="gm-panel-n3w" />
						<input type="hidden" name="sp" value="logs" />
					
						<div class="group user-group text-center" style="display: inline-block;">
							<div class="group" > 
								<label>UserID</label> 
								<input id="user-input" type="text" name="username" placeholder="UserID" value="<?= $userId ?>">
							</div>
							<p class="gray">or</p>
							<div class="group"> 
								<label>Charname</label> 
								<input id="char-input" type="text" name="charname" placeholder="CharName" value="<?= $charName ?>"> 
							</div>
							
						</div>
							
						<div class="group text-center" style="display: inline-block;"> 
							<label>Action type</label> 
							<select id="action-type" name="action-type[]" style="width: 350px; background-image: none;" size="10" multiple>
								<?php foreach ($ActionTypes as $key => $name) : ?>
									<option value="<?= $key ?>" <?= in_array($key, $actionTypes) ? "selected" : "" ?>>
										<?= $key ?> - <?= $name ?>
									</option>
								<?php endforeach ?>
							</select>
							<p class='yellow-gray' style='font-size: 12px; color: gray; cursor: pointer;' onclick="resetActionType()">Reset<p>
						</div>
						
						<div class="group user-group" style="display: inline-block;">
						
							<div class="group"> 
								<div class="middle-checkbox"> 
									<input id="date-start-checkbox" type="checkbox" name="date-start-enabled" <?= isset($_GET["date-start-enabled"]) ? "checked" : "" ?> />
									<label for="date-start-checkbox">Date start</label> 
								</div>
								<input id="date-start" type="datetime-local" name="date-start" 
									value="<?= $dateStart ? date("Y-m-d\TH:i", strtotime($dateStart)) : date("Y-m-d\TH:i") ?>" style="margin: 0 10px;">
							</div>
							
							<div class="group"> 
								<div class="middle-checkbox" > 
									<input id="date-end-checkbox" type="checkbox" name="date-end-enabled" <?= isset($_GET["date-end-enabled"]) ? "checked" : "" ?> />
									<label for="date-end-checkbox">Date end</label> 
								</div>
								<input id="date-end" type="datetime-local" name="date-end" 
									value="<?= $dateEnd ? date("Y-m-d\TH:i", strtotime($dateEnd)) : date("Y-m-d\TH:i") ?>" style="margin: 0 10px;">
							</div>
							
						</div>
						  
						<div class="group" style="position: absolute; right: 0;">   						
							<div class="group" style="display: inline-block;"> 
								<label>Rows</label> 
								<input id="rows-input" type="number" name="rows" placeholder="Rows" value="<?= $rows ?>" step="100"> 
							</div>   
						  <input type="submit" value="Show" 
							style="vertical-align: bottom; margin: 5px; width: 170px;">
						</div>
					
					</form>	
					
				</div>
				
				<!-- START DEFAULT DATATABLE -->
				<div class="panel panel-default" style="margin-top: 60px;">
					<div class="panel-header" style="height: 40px; vertical-align: middle; line-height: 40px;">
						<div style="display: inline-block; vertical-align: middle;">
							<h3>Displayed rows: <?= $stmt->rowCount() ?></h3>
						</div>
						<div class='right text-center'>
							<div class="middle-checkbox"> 
								<input id="date-checkbox" type="checkbox" onclick="$('.date-column').toggleClass('hidden')" checked />
								<label for="date-checkbox">Date</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="userid-checkbox" type="checkbox" onclick="$('.userid-column').toggleClass('hidden')" checked />
								<label for="userid-checkbox">UserID</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="charname-checkbox" type="checkbox" onclick="$('.charname-column').toggleClass('hidden')" checked />
								<label for="charname-checkbox">Charname</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="level-checkbox" type="checkbox" onclick="$('.level-column').toggleClass('hidden')" checked />
								<label for="level-checkbox">Level</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="map-checkbox" type="checkbox" onclick="$('.map-column').toggleClass('hidden')" checked />
								<label for="map-checkbox">Map</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="action-checkbox" type="checkbox" onclick="$('.action-column').toggleClass('hidden')" checked />
								<label for="action-checkbox">Action</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="info1-checkbox" type="checkbox" onclick="$('.info1-column').toggleClass('hidden')" checked />
								<label for="info1-checkbox">Info1</label> 
							</div>
							<div class="middle-checkbox"> 
								<input id="info2-checkbox" type="checkbox" onclick="$('.info2-column').toggleClass('hidden')" checked />
								<label for="info2-checkbox">Info2</label> 
							</div>
						</div>
					</div>
					<div class="panel-body">
						<table id="logs-table" class="table datatable text-center" style="width: 100%;">
							<thead>
								<tr>
									<th class="date-column sorting_asc">Date</th>
									<th class="userid-column">UserID</th>
									<th class="charname-column">CharName</th>
									<th class="level-column">Lv</th>
									<th class="map-column">Map</th>
									<th class="action-column">Action</th>
									<th class="info1-column">Info 1</th>
									<th class="info2-column">Info 2</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									$action = $row["ActionType"];
									include("modules/action-row.php");
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- END DEFAULT DATATABLE -->
			</div>
		</div>

    </div>
</div>
<script>
$(function() {
	var page = $("#page");
    $('body').empty().append(page);
});
function showByUserId(userId) {
	$("#user-input").val(userId);
	$("#search-form").submit();
}
function showByCharname(charname) {
	$("#char-input").val(charname);
	$("#search-form").submit();
}
function resetActionType() {
	$("#action-type option:selected").removeAttr('selected');
}
function setStartDate(date) {
	$("#date-start-checkbox").attr("checked", "true");
	$("#date-start").val(date);
}
function setEndDate(date) {
	$("#date-end-checkbox").attr("checked", "true");
	$("#date-end").val(date);
}
function selectActionType(actionType) {
	$("#action-type option[value=" + actionType + "]").attr('selected', 'selected');
}
</script>