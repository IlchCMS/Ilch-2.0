<?php
$errors = $this->get('errors');
?>

<?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="regist panel panel-default">
        <div class="panel-heading">
            <?=$this->getTrans('logindata') ?>
        </div>
        <div class="panel-body">
            <div class="form-group hidden">
                <label class="col-lg-2 control-label">
                    <?=$this->getTrans('bot') ?>*
                </label>
                <div class="col-lg-8">
                    <input type="text"
                           class="form-control"
                           name="bot"
                           placeholder="Bot" />
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('name') ? 'has-error' : '' ?>">
                <label for="name" class="control-label col-lg-2">
                    <?=$this->getTrans('name') ?>:
                </label>
                <div class="col-lg-8">
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           value="<?= $this->originalInput('name') ?>" />
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('password') ? 'has-error' : '' ?>">
                <label for="password" class="control-label col-lg-2">
                    <?=$this->getTrans('password') ?>:
                </label>
                <div class="col-lg-8">
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           value="<?= $this->originalInput('password') ?>" />
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('password2') ? 'has-error' : '' ?>">
                <label for="password2" class="control-label col-lg-2">
                    <?=$this->getTrans('password2') ?>:
                </label>
                <div class="col-lg-8">
                    <input type="password"
                           class="form-control"
                           id="password2"
                           name="password2"
                           value="<?= $this->originalInput('password2') ?>" />
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
                <label for="email" class="control-label col-lg-2">
                    <?=$this->getTrans('emailAdress') ?>:
                </label>
                <div class="col-lg-8">
                    <input type="text"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?= $this->originalInput('email') ?>" />
                </div>
            </div>
            <?php if ($this->get('captchaNeeded')) : ?>
            <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
                <label class="col-lg-2 control-label">
                    <?=$this->getTrans('captcha') ?>:
                </label>
                <div class="col-lg-8">
                    <?=$this->getCaptchaField() ?>
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('captcha') ? 'has-error' : '' ?>">
                <div class="col-lg-offset-2 col-lg-8 input-group captcha">
                    <input type="text"
                           class="form-control"
                           id="captcha-form"
                           name="captcha"
                           autocomplete="off"
                           placeholder="<?=$this->getTrans('captcha') ?>" />
                    <span class="input-group-addon">
                        <a href="javascript:void(0)" onclick="
                            document.getElementById('captcha').src='<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                            document.getElementById('captcha-form').focus();"
                            id="change-image">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="panel-footer clearfix">
            <div class="pull-right">
                <?=$this->getSaveBar('registButton', 'Regist') ?>
            </div>
        </div>
    </div>
</form>

<script src="<?=$this->getStaticUrl('../application/modules/user/static/js/pStrength.jquery.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('#password').pStrength({
        'bind': 'keyup change', // When bind event is raised, password will be recalculated;
        'changeBackground': true, // If true, the background of the element will be changed according with the strength of the password;
        'backgrounds'     : [['#FFF', '#000'], ['#d52800', '#000'], ['#ee6002', '#000'], ['#ff8a00', '#000'],
                            ['#ffb400', '#000'], ['#e4c100', '#000'], ['#b2e20c', '#000'], ['#93d200', '#000'],
                            ['#7dc401', '#000'], ['#73b401', '#000'], ['#4db401', '#000'], ['#46a501', '#000'], ['#409601', '#000']], // Password strength will get values from 0 to 12. Each color in backgrounds represents the strength color for each value;
        'passwordValidFrom': 60, // 60% // If you define a onValidatePassword function, this will be called only if the passwordStrength is bigger than passwordValidFrom. In that case you can use the percentage argument as you wish;
        'onValidatePassword': function(percentage) { }, // Define a function which will be called each time the password becomes valid;
        'onPasswordStrengthChanged' : function(passwordStrength, percentage) { } // Define a function which will be called each time the password strength is recalculated. You can use passwordStrength and percentage arguments for designing your own password meter
    });
});
</script>
