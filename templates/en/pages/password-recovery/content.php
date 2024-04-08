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
-->


<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Password recovery</a></span>
	</div>
    <div class="page-body border_box self_clear">
		
			<div id='recoverAnswer'>
				<center>
					<form method="post" action="<?= $TemplateUrl ?>/actions/forgot-password/password-recovery.php" >
						<table>
							<tr>
								<td><label for="register_password_confirm">Username:&nbsp;</label></td>
								<td><input id="userid" name="userid" type="text" minlenght="4" maxlength="18" required
										   placeholder="Username" style="width: 290px;"></td>
							</tr>
							<br>
							<tr>
								<td><label for="register_password_confirm">Secret Key:</label></td>
								<td><input id="answer" name="answer" type="text" minlenght=8 maxlength="8" required
										   placeholder="code 8 digits" style="width: 290px;"></td>
							</tr>
							<br>
							<!--
							<tr>
							<td></td>
							<td>
								<div id="captcha"></div>
							</td>
							</tr>
							-->
							
						</table>
						
						
						<div id="submit"><input type="submit" value="Submit" class="btn"/></div>
					</form>
				</center>
			</div>
		
    </div>
</div>
<!--
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>
-->