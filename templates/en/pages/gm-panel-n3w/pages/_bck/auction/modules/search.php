<div class="search-container" style="height: 120px;">
	<form style="text-align: center; margin: 0 0 20px 0;">
		<input type="hidden" name="p" value="gm-panel-n3w" />
		<input type="hidden" name="sp" value="auction" />		
					
		<div class="group user-group text-center" style="display: inline-block; width: auto;">
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label>UserID</label> 
				<input id="user-input" type="text" name="username" placeholder="UserID" value="<?= $userId ?>">
			</div>
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label>Charname</label> 
				<input id="char-input" type="text" name="charname" placeholder="CharName" value="<?= $charName ?>"> 
			</div>			
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label>MarketID</label> 
				<input id="marketid-input" type="text" name="marketid" placeholder="MarketID" value="<?= $marketId ?>"> 
			</div>			
		</div>
		
		<div class="group user-group text-center" style="display: inline-block;">
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label for="any-radio">Any status</label> 
				<input id="any-radio" type="radio" name="status" value="any" <?= $statuses == "any" ? "checked" : "" ?> >
			</div>
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label for="active-radio">Active</label> 
				<input id="active-radio" type="radio" name="status" value="active" <?= $statuses == "active" ? "checked" : "" ?> >
			</div>			
			<div class="group" style="display: inline-block; margin: 0 10px;"> 
				<label for="finished-radio">Finished</label> 
				<input id="finished-radio" type="radio" name="status" value="finished" <?= $statuses == "finished" ? "checked" : "" ?> >
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