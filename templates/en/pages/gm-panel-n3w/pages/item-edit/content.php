<?php
// Verifica se è stato fornito un UID valido
if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {
    return;
}
$uid = $_GET["uid"];

// Query per cercare l'elemento nell'inventario
$stmt = $conn->prepare("SELECT I.ItemName, I.Slot AS SlotCount, CI.*, I.Str, I.Dex, I.Rec, I.Int, I.Wis, I.Luc, I.Hp, I.Mp, I.Sp, I.Enchant AS Craftname FROM PS_GameData.dbo.CharItems CI 
                        LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID
                        WHERE ItemUID=:uid");
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Se non trovato nell'inventario, cerca nel magazzino
if (!$item) {
    $stmt = $conn->prepare("SELECT I.ItemName, I.Slot AS SlotCount, CI.*, I.Str, I.Dex, I.Rec, I.Int, I.Wis, I.Luc, I.Hp, I.Mp, I.Sp, I.Enchant AS Craftname FROM PS_GameData.dbo.UserStoredItems CI 
                            LEFT JOIN PS_GameDefs.dbo.Items I ON I.ItemID=CI.ItemID
                            WHERE ItemUID=:uid");
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se non trovato nemmeno nel magazzino, restituisci un messaggio di errore
    if (!$item) {
        echo "<h2 color='red'>Elemento non trovato</h2>";
        return;
    }
}

// Recupera l'elenco dei gioielli disponibili
$stmt = $conn->prepare("SELECT TypeID, ItemName FROM PS_GameDefs.dbo.Items WHERE Type = 30 AND Count > 0");
$stmt->execute();
$gems = [0 => "Empty"];
while ($gem = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $gems[$gem["TypeID"]] = $gem["ItemName"];
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