<?php
if ($UserUID) {
    return; // Se l'utente è già loggato, esce dallo script
}
?>
<!-- quickRegister.Start -->
<div class="quickregister vertical_center">
    <div class="container">
        <div class="sw_row">
            <div class="sw_col col-m"></div>

            <div class="sw_col col-s">
                <div class="quickregister_title overflow_ellipsis align_right"
                     title="Register an account - Easy registration to join our server today!">
                    Register an account
                    <i>Easy registration to join our server today!</i>
                </div>

                <form action="<?= $TemplateUrl ?>actions/register.php" method="post" accept-charset="utf-8">
                    <div class="form_field">
                        <input type="text" name="username" placeholder="Username" minlength="4" maxlength="20" required />
                        <span class="field_icon"><i class="sw_icon icon_username vertical_center"></i></span>
                    </div>

                    <div class="form_field">
                        <input type="email" name="email" placeholder="Email" required />
                        <span class="field_icon"><i class="sw_icon icon_email vertical_center"></i></span>
                    </div>

                    <div class="form_field">
                        <input type="password" name="password" placeholder="Password" minlength="5" required />
                        <span class="field_icon"><i class="sw_icon icon_password vertical_center"></i></span>
                    </div>

                    <div class="form_field">
                        <input type="password" name="password_confirm" placeholder="Confirm password" minlength="5" required />
                        <span class="field_icon"><i class="sw_icon icon_password vertical_center"></i></span>
                    </div>

                    <div class="form_field last">
                        <input type="submit" name="login_submit" value="Register new account" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- quickRegister.End -->
