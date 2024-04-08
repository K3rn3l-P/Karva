<?php
$charName = isset($_GET["charname"]) ? GetClear($_GET["charname"]) : "";
$userId = isset($_GET["userid"]) ? GetClear($_GET["userid"]) : "";
$itemUid = isset($_GET["itemuid"]) && $_GET["itemuid"] && is_numeric($_GET["itemuid"]) > 0 ? $_GET["itemuid"] : 0;
$rows = isset($_GET["rows"]) && is_numeric($_GET["rows"]) && $_GET["rows"] > 0 ? $_GET["rows"] : 100;

$charId = 0;
$userUid = 0;
if (!$itemUid && $charName) {
    $stmt = $conn->prepare("SELECT CharID FROM PS_GameData.dbo.Chars WHERE CharName = ?");
    $stmt->execute([$charName]);
    $charId = $stmt->rowCount() ? $stmt->fetchColumn() : 0;
} elseif (!$itemUid && $userId) {
    $stmt = $conn->prepare("SELECT UserUID FROM PS_UserData.dbo.Users_Master WHERE UserID = ?");
    $stmt->execute([$userId]);
    $userUid = $stmt->rowCount() ? $stmt->fetchColumn() : 0;
}
include_once("modules/craftname.php");

$condition = "";
$isWarehouse = false;

if ($itemUid) { // By ItemUID
    // Find item in warehouse
    $stmt = $conn->prepare("SELECT 1 FROM PS_GameData.dbo.UserStoredItems WHERE ItemUID = ?");
    $stmt->execute([$itemUid]);
    if ($stmt->rowCount()) {
        $isWarehouse = true;
        $query = "SELECT TOP $rows I.ItemName, I.ReqDex, UM.UserUID, UM.UserID, UI.*
            FROM PS_GameData.dbo.UserStoredItems UI
            LEFT JOIN PS_GameDefs.dbo.Items I ON UI.ItemID = I.ItemID
            LEFT JOIN PS_UserData.dbo.Users_Master UM ON UI.UserUID = UM.UserUID
            WHERE UI.ItemUID = ?
            ORDER BY UI.Slot";
    } else {
        $query = "SELECT TOP $rows I.ItemName, I.ReqDex, C.CharName, C.UserUID, C.UserID, UI.*
            FROM PS_GameData.dbo.CharItems UI
            LEFT JOIN PS_GameDefs.dbo.Items I ON UI.ItemID = I.ItemID
            LEFT JOIN PS_GameData.dbo.Chars C ON UI.CharID = C.CharID
            WHERE UI.ItemUID = ?
            ORDER BY UI.Bag, UI.Slot";
    }
} elseif ($charId) { // By CharID
    $query = "SELECT TOP $rows I.ItemName, I.ReqDex, C.CharName, C.UserUID, C.UserID, UI.*
        FROM PS_GameData.dbo.CharItems UI
        LEFT JOIN PS_GameDefs.dbo.Items I ON UI.ItemID = I.ItemID
        LEFT JOIN PS_GameData.dbo.Chars C ON UI.CharID = C.CharID
        WHERE UI.CharID = ?
        ORDER BY UI.Bag, UI.Slot";
} else { // By UserUID
    $isWarehouse = true;
    $query = "SELECT TOP $rows I.ItemName, I.ReqDex, UM.UserUID, UM.UserID, UI.*
        FROM PS_GameData.dbo.UserStoredItems UI
        LEFT JOIN PS_GameDefs.dbo.Items I ON UI.ItemID = I.ItemID
        LEFT JOIN PS_UserData.dbo.Users_Master UM ON UI.UserUID = UM.UserUID
        WHERE UI.UserUID = ?
        ORDER BY UI.Slot";
}

$stmt = $conn->prepare($query);
$stmt->execute([$itemUid ?: ($charId ?: $userUid)]);

$charnameClass = ($charId || $userUid) ? "hidden" : "";
$itemuidClass = $itemUid ? "hidden" : "";
?>
<div class="page" id="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a href="/?p=gm-panel-n3w">GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
	<?= $alerts ?>	
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
							<input id="charname-checkbox" type="checkbox" onclick="$('.charname-column').toggleClass('hidden')" <?= $charId || $userUid ? "" : "checked" ?> />
							<label for="charname-checkbox"><?= $isWarehouse ? "UserID" : "CharName" ?></label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="itemuid-checkbox" type="checkbox" onclick="$('.itemuid-column').toggleClass('hidden')" <?= $itemUid ? "" : "checked" ?> />
							<label for="itemuid-checkbox">ItemUID</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="bag-checkbox" type="checkbox" onclick="$('.bag-column').toggleClass('hidden')" checked />
							<label for="bag-checkbox">Bag</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="slot-checkbox" type="checkbox" onclick="$('.slot-column').toggleClass('hidden')" checked />
							<label for="slot-checkbox">Slot</label> 
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
							<input id="gems-checkbox" type="checkbox" onclick="$('.gems-column').toggleClass('hidden')" checked />
							<label for="gems-checkbox">Gems</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="maketime-checkbox" type="checkbox" onclick="$('.maketime-column').toggleClass('hidden')" checked />
							<label for="maketime-checkbox">Maketime</label> 
						</div>
						<div class="middle-checkbox"> 
							<input id="action-checkbox" type="checkbox" onclick="$('.action-column').toggleClass('hidden')" checked />
							<label for="action-checkbox">Actions</label> 
						</div>
					</div>
				</div>
				<div class="panel-body">
					<table class="table-center text-center" style="width: 100%;">
						<tr>
							<th class="charname-column <?= $charId || $userUid ? "hidden" : "" ?>"><?= $isWarehouse ? "UserID" : "CharName" ?></th>
							<th class="itemuid-column <?= $itemUid ? "hidden" : "" ?>">ItemUID</th>
							<th class="bag-column" style="width: 80px">Bag</th>
							<th class="slot-column" style="width: 80px">Slot</th>
							<th class="item-column" style="width: 230px">Item</th>
							<th class="count-column" style="width: 50px">Count</th>
							<th class="iteminfo-column">Item info</th>
							<th class="gems-column">Gems</th>
							<th class="maketime-column">Maketime</th>
							<th class="action-column">Actions</th>
						</tr>
						<?php
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							include("item.php");
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
    });
</script>