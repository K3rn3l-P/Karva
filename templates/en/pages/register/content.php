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
<script src="/js/Barrapw.js"></script>
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
                <td><label for="login_password" data-tip="Password must be 8-20 characters long">Password<span class="red">*</span></label></td>
<td>
    <input type="password" id="password" name="password" value="" onkeyup="checkPasswordStrength(this.value)">
    <span id="password_error"></span>
    <div id="password-strength-meter">
        <small class="progress-bar" id="password-strength"></small>
    </div>
</td>
</tr>
<tr>
<td><label for="login_password" data-tip="Confirm Password">Confirm<span class="red">*</span></label></td>
<td>
    <input type="password" id="password_confirm" name="password_confirm" onkeyup="checkPasswordMatch()">
    <span id="confirm_error"></span>
    <div id="password-match"></div>
    <div class="password-buttons">
    <button type="button" onclick="generatePassword()">Generate Password</button>
    <button type="button" onclick="togglePasswordVisibility()">Show Password</button>
</div>
</td>

    <div>

</div>
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

<script>
function generatePassword() {
    // Caratteri consentiti dalla funzione di controllo della robustezza della password
    var allowedChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789$@$!%*#?&";

    // Lunghezza casuale della password tra 8 e 20 caratteri
    var passwordLength = Math.floor(Math.random() * (20 - 8 + 1)) + 8;
    var password = "";

    // Genera la password utilizzando solo i caratteri consentiti
    for (var i = 0; i < passwordLength; i++) {
        var randomNumber = Math.floor(Math.random() * allowedChars.length);
        password += allowedChars.substring(randomNumber, randomNumber + 1);
    }

    // Imposta la password generata nei campi password
    document.getElementById("password").value = password;
    document.getElementById("password_confirm").value = password;

    // Controlla la forza della password appena generata
    checkPasswordStrength(password);
}

function togglePasswordVisibility() {
    var passwordField = document.getElementById("password");
    var confirmPasswordField = document.getElementById("password_confirm");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        confirmPasswordField.type = "text";
    } else {
        passwordField.type = "password";
        confirmPasswordField.type = "password";
    }
}
</script>