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
		<span class="latest_news vertical_center"> <a>User panel</a> &rarr; <i >Change Password</i></span>
	</div>
    <div class="page-body border_box self_clear">

		

		<div class="ucp_divider"></div>

		<form method="post" action="<?= $TemplateUrl ?>actions/settings/change-password.php" accept-charset="UTF-8">
			<table class="page_form">
				<tbody>
				<tr>
					<td><label>Old password</label></td>
					<td><input type="password" name="old-password"></td>
				</tr>
				<tr>
					<td><label>New password</label></td>
					<td><input type="password" name="new-password"></td>
				</tr>
				<tr>
					<td><label>Confirm password</label></td>
					<td><input type="password" name="password-confirm"></td>
				</tr>
				<tr>
					<!--
                    <td></td>
                    <td>
					     <div id="captcha"></div>
                    </td>
				</tr>
				-->				
				</tbody>
			</table>
			<center style="margin-bottom:10px;">
				<input type="submit" value="Change password">
			</center>
		</form>
	
	</div>
</div>
<!--
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>
-->