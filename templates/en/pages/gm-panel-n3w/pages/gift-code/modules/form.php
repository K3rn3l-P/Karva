<div class="container">
  
  <form class="beauty-form" method="POST" action="<?= $TemplateUrl ?>actions/gm-panel-n3w/gift-code/add-code.php">
    
    <div class="group"> 
      <label>Create Code</label> 
      <input type="text" name="code" placeholder="XXXX-XXXX-XXXX-XXXX-XXXX" value="">
      <span class="highlight"></span>
      <span class="bar"></span>
    </div>
      
      
    <div class="group"> 
      <label>Item ID</label>  
      <input type="number" name="itemid" step="1" value="0" >
      <span class="highlight"></span>
      <span class="bar"></span>
    </div>
      
    <div class="group">  
      <label>ItemID Count (max x255)</label>
      <input type="number" name="count" step="1" value="0">
      <span class="highlight"></span>
      <span class="bar"></span>
      
    </div>
	
    <div class="group"> 
      <label>SP</label> 
      <input type="number" name="sp" step="1" value="0">
      <span class="highlight"></span>
      <span class="bar"></span>
      
    </div>
      
    <div class="group">
      <label>End date</label>  
      <input type="date" name="enddate" value="<?= date('Y-m-d', strtotime(date('Y-m-d') . "+1 months") ) ?>" >
      <span class="highlight"></span>
      <span class="bar"></span>
      
    </div>
      
    <div class="group"> 
      <label class="checkbox-label">For each user</label> 
      <input type="checkbox" name="feu" class="checkbox" >
      
    </div>
      
    <div class="group">      
      <input type="submit" value="Add" >
    </div>
    
  </form>
  
</div>
<br><br>