<!--
<script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('captcha', {
          'sitekey' : '6Le-IkEaAAAAAJC3125-lsS_1NAn4bME2cRaBgF-',
		  'expired-callback': resetCap,
          'theme' : 'dark'
        });
      };
        function resetCap(){
            grecaptcha.reset();
        }
</script>


<div class="form_wrapper">
	<form action="<?= $TemplateUrl ?>actions/reward/gift-code.php" id="form_gift_redemption_display" method="post">
		<div class="form-item" id="form-item-edit-event_key">
			<label for="edit-event_key">Insert Code:</label> 
			<input class="form-text field-default-value" id="edit-event_key" name="code" size="60" type="text" value="" placeholder="XXXX-XXXX-XXXX-XXXX-XXXX"  style="width: 394px;">
			<input class="form-submit" id="redeem_btn" type="submit" value="Redeem">
			<center><div style="margin-left:5px;" id="captcha"></div></center>
		</div>
	</form>			
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>
-->