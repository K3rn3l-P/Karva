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
		<span class="latest_news vertical_center"> <a>Registration</a></span>
	</div>
	
    <div class="page-body border_box self_clear">
        <form action="<?= $TemplateUrl ?>/actions/register.php" method="post" accept-charset="utf-8" class="page_form">
			<center><b><span style="color:red">IMPORTANT</span></b></br>
	<p>Do not use same password you used in other shaiya servers!</p>
	</center>

		  <table>
                <tbody>
                <tr>
                    <td><label for="login_username">Username<span class="red">*</span></label></td>
                    <td>
                        <input id="login_username" type="text" name="username" value="">
                        <span id="username_error"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="login_email" data-tip="Please provide valid email address">E-Mail<span class="red">*</span></label></td>
                    <td>
                        <input id="login_email" type="email" name="email" value="">
                        <span id="email_error"></span>
                    </td>
                </tr>
				
				
                <tr>
                    <td><label for="login_password" data-tip="Password must be 5-15 characters long">Password<span class="red">*</span></label></td>
                    <td>
                        <input type="password" name="password" value="">
                        <span id="password_error"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="login_password" data-tip="Confirm Password">Confirm<span class="red">*</span></label></td>
                    <td>
                        <input type="password" name="password_confirm" value="">
                        <span id="confirm_error"></span>
                    </td>
                </tr>
				<!--
				<tr>						
				<td>
                    <td>
					     <div id="captcha"></div>
                    </td>
				</tr>
                -->
                </tbody>
              
            </table>
			
            <center>
                <input type="submit" value="Register Account">
                <section id="forgot"><a href="/?p=login">Already registered?</a></section>
				
            </center>
			
        </form>
		
    </div>
</div>
<!--
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
-->