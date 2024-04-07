<div class="container">
  
  <form class="beauty-form" method="POST" action="<?= $TemplateUrl ?>actions/gm-panel-n3w/giftbox/add-item.php">
    
    <div class="group"> 
      <label>UserID</label> 
      <input type="text" name="UserID" placeholder="UserID" value="">
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
      <label>Item Count</label>
      <input type="number" name="count" step="1" value="0" max="255">
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