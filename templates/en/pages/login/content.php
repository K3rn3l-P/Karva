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

<div id="page-login" class="page page-login">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Login</a></span>
	</div>
    <div class="page-body border_box self_clear">
        <form action="<?= $TemplateUrl ?>/actions/login.php" method="post" accept-charset="utf-8" class="page_form">
            <table>
                <tbody>
                <tr>
                    <td><label for="login_username">Username</label></td>
                    <td>
                        <input type="text" name="username" id="login_username" value="">
                        <span id="username_error"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="login_password">Password</label></td>
                    <td>
                        <input type="password" name="password" id="login_password" value="">
                        <span id="password_error"></span>
                    </td>
                </tr>
				<!--
				<tr>
                    <td></td>
                    <td>
					     <div id="captcha"></div>
                    </td>
				</tr>
				-->
				
                </tbody>
            </table>

            <center>
                <input type="submit" name="login_submit" value="Log in!">
                <section id="forgot"><a href="/?p=password-recovery">Have you lost your password?</a></section>
            </center>
        </form>
    </div>
</div>
<!--
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>
-->