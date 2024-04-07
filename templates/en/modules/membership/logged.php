

<section id="sidebox_info_login" class="sidebox">

<div class="sidebox_body border_box">

<center>
<input type="submit" value="USER PANEL" 
    onclick="window.location='/?p=ucp';" /> 	
	
<?php if ($IsStaff) : ?>
<input type="submit" value="ADMIN PANEL" 
    onclick="window.location='/?p=gm-panel-n3w';" /> 	
<?php endif ?>	
	
<input type="submit" value="LOGOUT" 
    onclick="window.location='<?= $TemplateUrl ?>actions/logout.php';" /> 	
	</center>
</div>

</section>
