

<?php if (!$UserUID) : ?>
<section id="sidebox_info_login" class="sidebox">
    

    <div class="sidebox_body border_box">
        
<center>	
<input type="submit" value="Login" 
    onclick="window.location='/?p=login';" />    

<input type="submit" value="Create Account" 
    onclick="window.location='/?p=register';" />    

</center>
    </div>
</section>
<?php endif ?>



 <div>
            <?php include_once($UserUID ? "membership/logged.php" : "membership/not-logged.php") ?>
</div>

