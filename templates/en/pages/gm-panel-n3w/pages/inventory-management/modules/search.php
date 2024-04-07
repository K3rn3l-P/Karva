<div class="search-container" style="height: 120px;">
	<form style="text-align: center; margin: 0 0 20px 0;">
		<input type="hidden" name="p" value="gm-panel-n3w" />
		<input type="hidden" name="sp" value="inventory-management" />		
					
		<div class="group user-group text-center" style="display: inline-block; width: auto;">
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label>UserID</label> 
				<input id="user-input" type="text" name="userid" placeholder="UserID" value="<?= $userId ?>"> 
			</div>
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label>Charname</label> 
				<input id="char-input" type="text" name="charname" placeholder="CharName" value="<?= $charName ?>"> 
			</div>
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label>ItemUID</label> 
				<input id="itemuid-input" type="text" name="itemuid" placeholder="ItemUID" value="<?= $itemUid ?>"> 
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