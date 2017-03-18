<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <legend><?=$this->getTrans('settingsPassword'); ?></legend>
            <?php if ($this->validation()->hasErrors()): ?>
                <div class="alert alert-danger" role="alert">
                    <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
                    <ul>
                        <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                            <li><?=$error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" class="form-horizontal" method="POST">
                <?=$this->getTokenField(); ?>
                <div class="form-group <?=$this->validation()->hasError('password') ? 'has-error' : '' ?>">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileNewPassword'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               value=""
                               required />
                        <?=$this->getTrans('profilePasswordInfo'); ?>
                    </div>
                </div>
                <div class="form-group <?=$this->validation()->hasError('password2') ? 'has-error' : '' ?>">
                    <label class="col-lg-2 control-label">
                        <?=$this->getTrans('profileNewPasswordRetype'); ?>*
                    </label>
                    <div class="col-lg-8">
                        <input type="password"
                               class="form-control"
                               name="password2"
                               value=""
                               required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-8">
                        <input type="submit"
                               class="btn"
                               name="saveEntry"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
