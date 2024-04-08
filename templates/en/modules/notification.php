<div id="topbar" class="topbar">
    <div class="topbar-inner border_box anti_blur self_clear">
        <div class="topbar-left">
            <div class="topbar-noti">
                <div class="noti_title">[Notification]:</div>
                <div class="noti_text">
                    <p>Welcome to <?= $ServerName ?>. Please 
                        <a href="/?p=register" data-hasevent="1">create a new account</a> or 
                        <a href="/?p=login" data-hasevent="1">login</a> to unlock more features.
                    </p>
                </div>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-misc">
                <select onchange="location = this.value;">
                    <option disabled>Select Server</option>
                    <?php foreach ($Languages as $key => $name) : ?>
                        <option value="/?lang=<?= $key ?>" <?= $lang == $key ? "selected" : "" ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
</div>
