<?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <?php $errors = $this->get('errors'); ?>
    <div class="form-group <?php if (!empty($errors['name'])) { echo 'has-error'; }; ?>">
        <label for="name" class="control-label col-lg-2">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-8">
            <input type="text"
                   name="name"
                   class="form-control"
                   id="name" />
            <?php if (!empty($errors['name'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['name']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group <?php if (!empty($errors['password'])) { echo 'has-error'; }; ?>">
        <label for="password" class="control-label col-lg-2">
            <?=$this->getTrans('password') ?>:
        </label>
        <div class="col-lg-8">
            <input type="password"
                   class="form-control"
                   name="password"
                   id="password" />
            <?php if (!empty($errors['password'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['password']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group <?php if (!empty($errors['password2'])) { echo 'has-error'; }; ?>">
        <label for="password2" class="control-label col-lg-2">
            <?=$this->getTrans('password2') ?>:
        </label>
        <div class="col-lg-8">
            <input type="password"
                   class="form-control"
                   name="password2"
                   id="password2" />
            <?php if (!empty($errors['password2'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['password2']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group <?php if (!empty($errors['email'])) { echo 'has-error'; }; ?>">
        <label for="email" class="control-label col-lg-2">
            <?=$this->getTrans('emailAdress') ?>:
        </label>
        <div class="col-lg-8">
            <input type="text"
                   name="email"
                   class="form-control"
                   id="email" />
            <?php if (!empty($errors['email'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['email']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group <?php if (!empty($errors['captcha'])) { echo 'has-error'; }; ?>">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('captcha') ?>:
        </label>
        <div class="col-lg-8">
            <?=$this->getCaptchaField() ?>
        </div>
    </div>
    <div class="form-group <?php if (!empty($errors['email'])) { echo 'has-error'; }; ?>">
        <div class="col-lg-offset-2 col-lg-8 input-group captcha">
            <input type="text"
                  id="captcha-form"
                  class="form-control"
                  autocomplete="off"
                  name="captcha"
                  placeholder="<?=$this->getTrans('captcha') ?>" />
            <span class="input-group-addon">
                <a href="javascript:void(0)" onclick="
                    document.getElementById('captcha').src='<?=$this->getUrl()?>/application/libraries/Captcha/Captcha.php?'+Math.random();
                    document.getElementById('captcha-form').focus();"
                    id="change-image">
                    <i class="fa fa-refresh"></i>
                </a>
            </span>
        </div>
        <div class="col-lg-offset-2 col-lg-10">
            <?php if (!empty($errors['captcha'])): ?>
                <span class="help-inline"><?=$this->getTrans($errors['captcha']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8">
            <?=$this->getSaveBar('registButton', 'Regist') ?>
        </div>
    </div>
</form>

<script src="<?=$this->getStaticUrl('../application/modules/user/static/js/pStrength.jquery.js'); ?>"></script>
<script>
$(document).ready(function(){
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
