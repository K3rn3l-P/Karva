<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<div class="node format">
			<div class="container">
			  
				<form class="beauty-form" method="POST" action="<?= $TemplateUrl ?>actions/gm-panel-n3w/giftbox/add-item.php">
				
					<div class="group">
						<div class="group" style="display: inline-block;"> 
						  <label>UserID</label> 
						  <input id="user-input" type="text" name="username" placeholder="UserID" value="">
						</div>
						
						<div class="group" style="display: inline-block;"> 
						  <label>Charname</label> 
						  <input id="char-input" type="text" name="charname" placeholder="CharName" value=""> 
						</div>
						
						<div class="group" style="display: inline-block;"> 
						  <label class="checkbox-label">For each user</label> 
						  <input type="checkbox" name="feu" class="checkbox" onclick="$('#user-input, #char-input').attr('disabled', this.checked);" >      
						</div>
					</div>
					  			
					<div class="group">
						<div class="group" style="display: inline-block;"> 
						  <label>Item ID</label>  
						  <input type="number" name="itemid" step="1" value="0" >
						</div>
						  
						<div class="group" style="display: inline-block;">  
						  <label>Item Count</label>
						  <input type="number" name="count" step="1" value="1" min="1" max="255">
						</div>
					</div>
					  
					<div class="group right">      
					  <input type="submit" value="Add" >
					</div>
				
				</form>	
				
			</div>
		</div>
		

		<!-- START DEFAULT DATATABLE -->
		<div class="panel panel-default" style="margin-top: 60px;">
			<div class="panel-heading">                                
				<h2 class="panel-title">Adding logs</h2>
			</div>
			<div class="panel-body">
				<table class="table datatable text-center" style="width: 100%;">
					<thead>
						<tr>
							<th class="sorting_asc">Date</th>
							<th>UserID</th>
							<th>ItemID</th>
							<th>ItemName</th>
							<th>Count</th>
							<th>Slot</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$stmt = $conn->prepare("SELECT TOP 100 UM.UserID, I.ItemName, BL.* 
										FROM PS_WebSite.dbo.GiftBox_Log AS BL
										LEFT JOIN PS_UserData.dbo.Users_Master AS UM ON BL.UserUID=UM.UserUID
										LEFT JOIN PS_GameDefs.dbo.Items AS I ON BL.ItemID=I.ItemID
										ORDER BY RowID DESC");
						$stmt->execute();
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
							<tr>
								<td><?= date("d M H:i", strtotime($row["DT"])) ?></td>
								<td><a href='/?p=gm-panel-n3w&sp=user-search&UserUID=<?= $row["UserUID"] ?>' data-tip='Show user info'><?= $row["UserID"] ?></a></td>
								<td><?= number_format($row["ItemID"], 0, '.', ' ') ?></td>
								<td><?= $row["ItemName"] ?></td>
								<td><?= $row["ItemCount"] ?></td>
								<td><?= $row["Slot"] ?></td>
								<td><?= $row["ByUser"] ?></td>
							</tr>
						<?php endwhile ?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END DEFAULT DATATABLE -->
		<!-- end content -->	

    </div>
</div>