<?php
if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {
	return;
}
$uid = $_GET["uid"];

// Try to find item in inventory
$result = odbc_exec($odbcConn, "SELECT I.ItemName, I.Slot AS SlotCount, CI.* FROM PS_GameData.dbo.CharItems CI 
								LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID
								WHERE ItemUID=$uid");
$item = odbc_fetch_array($result);
// Try to find it in warehouse
if (!$item) {
	$result = odbc_exec($odbcConn, "SELECT I.ItemName, I.Slot AS SlotCount, CI.* FROM PS_GameData.dbo.UserStoredItems CI 
									LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID
									WHERE ItemUID=$uid");
	$item = odbc_fetch_array($result);
	// Not founded
	if (!$item) {
		echo "<h2 color='red'>Item not founded</h2>";
		return;
	}
}

if ($item["Craftname"]) {
	$item["Str"] = (int)substr($item["Craftname"], 0, 2);
	$item["Dex"] = (int)substr($item["Craftname"], 2, 2);
	$item["Rec"] = (int)substr($item["Craftname"], 4, 2);
	$item["Int"] = (int)substr($item["Craftname"], 6, 2);
	$item["Wis"] = (int)substr($item["Craftname"], 8, 2);
	$item["Luc"] = (int)substr($item["Craftname"], 10, 2);
	$item["Hp"] = (int)substr($item["Craftname"], 12, 2);
	$item["Mp"] = (int)substr($item["Craftname"], 14, 2);
	$item["Sp"] = (int)substr($item["Craftname"], 16, 2);
	$item["Enchant"] = (int)substr($item["Craftname"], 18, 2);
} else {
	$item["Str"] = 0;
	$item["Dex"] = 0;
	$item["Rec"] = 0;
	$item["Int"] = 0;
	$item["Wis"] = 0;
	$item["Luc"] = 0;
	$item["Hp"] = 0;
	$item["Mp"] = 0;
	$item["Sp"] = 0;
	$item["Enchant"] = 0;
}

$result = odbc_exec($odbcConn, "SELECT TypeID, ItemName FROM PS_GameDefs.dbo.Items WHERE Type=30 AND Count>0");
$gems = [0 => "Empty"];
while ($gem = odbc_fetch_array($result)) {
	$typeId = $gem["TypeID"];
	$gems[$typeId] = $gem["ItemName"];
}
?>

<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<div class="node format">
			<div class="container">
				<h1><?= $item["ItemName"] ?></h1>
			  
				<form class="beauty-form" method="POST" action="<?= $TemplateUrl ?>/actions/gm-panel-n3w/inventory-management/edit-item.php">
					<input type="hidden" name="uid" value="<?= $uid ?>" />
					
					<div class="group">
						<div class="group" style="display: inline-block;"> 
						  <label>TypeID</label>  
						  <input name="typeid" value="<?= $item["TypeID"] ?>" min="1" max="255">
						</div>
					</div>
				
					<div class="group">					
						<?php for ($i = 1; $i <= $item["SlotCount"]; $i++) : ?>
							<div class="group" style="display: inline-block;"> 
								<label>Gem #<?= $i ?></label> 
								<select name="gem-<?= $i ?>" style="width: 200px; background-image: none;">
									<?php foreach ($gems as $key => $name) : ?>
										<option value="<?= $key ?>" <?= $key == $item["Gem$i"] ? "selected" : "" ?>>
											<?= $key ?> - <?= $name ?>
										</option>
									<?php endforeach ?>
								</select>
							</div>
						<?php endfor ?>						
					</div>
					  			
					<div class="group">
						<div class="group" style="display: inline-block;"> 
						  <label>STR</label>  
						  <input type="number" name="str" value="<?= $item["Str"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>DEX</label>  
						  <input type="number" name="dex" value="<?= $item["Dex"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>REC</label>  
						  <input type="number" name="rec" value="<?= $item["Rec"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>INT</label>  
						  <input type="number" name="int" value="<?= $item["Int"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>WIS</label>  
						  <input type="number" name="wis" value="<?= $item["Wis"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>LUC</label>  
						  <input type="number" name="luc" value="<?= $item["Luc"] ?>" min="0" max="99">
						</div>						
					</div>
					
					<div class="group">
						<div class="group" style="display: inline-block;"> 
						  <label>HP</label>  
						  <input type="number" name="hp" value="<?= $item["Hp"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>SP</label>  
						  <input type="number" name="sp" value="<?= $item["Sp"] ?>" min="0" max="99">
						</div>
						<div class="group" style="display: inline-block;"> 
						  <label>MP</label>  
						  <input type="number" name="mp" value="<?= $item["Mp"] ?>" min="0" max="99">
						</div>
					</div>
					
					<div class="group">
						<div class="group" style="display: inline-block;"> 
						  <label>Enchant</label>  
						  <input type="number" name="enchant" value="<?= $item["Enchant"] ?>" min="0" max="99">
						</div>
					</div>
					  
					<div class="group right">      
					  <input type="submit" value="Change" >
					</div>
				
				</form>	
				
				<br><br><br><br>NOTE: <br>Enchant weapon value 10 for +10 <br>
				Enchant armor value 60 for +10
				
				
			</div>
		</div>

    </div>
</div>