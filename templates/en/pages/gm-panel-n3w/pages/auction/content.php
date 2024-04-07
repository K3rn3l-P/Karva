<?php
$userId = isset($_GET["username"]) ? GetClear($_GET["username"]) : "";
$charName = isset($_GET["charname"]) ? GetClear($_GET["charname"]) : "";
$marketId = isset($_GET["marketid"]) && $_GET["marketid"] && is_numeric($_GET["marketid"]) > 0 ? $_GET["marketid"] : 0;
$rows = isset($_GET["rows"]) && is_numeric($_GET["rows"]) && $_GET["rows"] > 0 ? $_GET["rows"] : 100;
$statuses = isset($_GET["status"]) ? $_GET["status"] : "any";

if ($userId) {
	$stmt = $conn->prepare("SELECT UserUID FROM PS_UserData.dbo.Users_Master WHERE UserID=?");
	$stmt->bindValue(1, $userId, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);						
	$userUid = $row ? $row['UserUID'] : 0;	
} 
if ($charName) {
	$stmt = $conn->prepare("SELECT CharID FROM PS_GameData.dbo.Chars WHERE CharName=?");
	$stmt->bindValue(1, $charName, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);						
	$charId = $row ? $row['CharID'] : 0;
}
include_once("modules/craftname.php");

$condition = "";
if ($marketId) {
	$condition = "AND M.MarketID=$marketId";
} elseif ($userId) {
	$condition = "AND M.CharID IN (SELECT CharID FROM PS_GameData.dbo.Chars WHERE UserUID=$userUid)";
} elseif (isset($charId)) {
	$condition = "AND M.CharID=$charId";
}
switch ($statuses) {
	case "active":
		$condition .= " AND M.Del=0";
		break;
	case "finished":
		$condition .= " AND M.Del=1";
		break;
}
$query = "SELECT TOP $rows I.ItemName, C.CharName, C.UserID, C.UserUID, RI.Result, M.*, MI.ItemID, MI.Count, MI.Gem1, MI.Gem2, MI.Gem3, MI.Gem4, MI.Gem5, MI.Gem6, MI.Craftname FROM PS_GameData.dbo.Market M
	LEFT JOIN PS_GameData.dbo.MarketItems MI ON M.MarketID=MI.MarketID
	LEFT JOIN PS_GameDefs.dbo.Items I ON MI.ItemID=I.ItemID
	LEFT JOIN PS_GameData.dbo.Chars C ON M.CharID=C.CharID
	LEFT JOIN PS_GameData.dbo.MarketCharResultItems RI ON M.MarketID=RI.MarketID
	WHERE 1=1 $condition
	ORDER BY M.RowID DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
?>  
<div class="page" id="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a href="/?p=gm-panel-n3w">GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear" style="padding: 5px 25px;">
	
		<!-- begin content -->
		<div class="node format">
			<?php include("modules/search.php") ?>
			
			<!-- START DEFAULT DATATABLE -->
			<div class="panel panel-default" style="margin-top: 60px;">
				<div class="panel-header" style="height: 40px; vertical-align: middle; line-height: 40px;">
					<div style="display: inline-block; vertical-align: middle;">
						<h3>Displayed rows: <?= $stmt->rowCount() ?></h3>
					</div>
					<div class='right text-center'>
						<div class="middle-checkbox"> 
							<input id="marketid-checkbox" type="checkbox" onclick="$('.marketid-column').toggleClass('hidden')" checked />
							<label for="marketid-checkbox">MarketID</label> 
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
							<input id="item-checkbox" type="checkbox" onclick="$('.item-column').toggleClass('hidden')" checked />
							<label for="item-checkbox">Item</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="count-checkbox" type="checkbox" onclick="$('.count-column').toggleClass('hidden')" checked />
							<label for="count-checkbox">Count</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="iteminfo-checkbox" type="checkbox" onclick="$('.iteminfo-column').toggleClass('hidden')" checked />
							<label for="iteminfo-checkbox">Item info</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="minmoney-checkbox" type="checkbox" onclick="$('.minmoney-column').toggleClass('hidden')" checked />
							<label for="minmoney-checkbox">Min money</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="buymoney-checkbox" type="checkbox" onclick="$('.buymoney-column').toggleClass('hidden')" checked />
							<label for="buymoney-checkbox">Buy money</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="bet-checkbox" type="checkbox" onclick="$('.bet-column').toggleClass('hidden')" checked />
							<label for="bet-checkbox">Bet</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="enddate-checkbox" type="checkbox" onclick="$('.enddate-column').toggleClass('hidden')" checked />
							<label for="enddate-checkbox">End date</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="status-checkbox" type="checkbox" onclick="$('.status-column').toggleClass('hidden')" checked />
							<label for="status-checkbox">Status</label> 
						</div>
					</div>
				</div>
				<div class="panel-body">
					<table class="table-center text-center" style="width: 100%;">
						<tr>
							<th class="marketid-column">MarketID</th>
							<th class="userid-column">UserID</th>
							<th class="charname-column">CharName</th>
							<th class="item-column" style="width: 180px">Item</th>
							<th class="count-column" style="width: 50px">Count</th>
							<th class="iteminfo-column">Item info</th>
							<th class="minmoney-column">Min money</th>
							<th class="buymoney-column">Buy money</th>
							<th class="bet-column">Bet</th>
							<th class="enddate-column">End date</th>
							<th class="status-column">Status</th>
						</tr>
						<?php
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							include("modules/item.php");
						}
						?>
					</table>
				</div>
			</div>
			<!-- END DEFAULT DATATABLE -->
		</div>
		<!-- end content -->

    </div>
</div>
<script>
$(function() {
	var page = $("#page");
    $('body').empty().append(page);
	$('.theme_havoc').removeClass('theme_havoc');
});
</script>