<?php
$errors = $this->get('errors');
$profil = $this->get('profil');
$profileFields = $this->get('profileFields');
$profileFieldsContent = $this->get('profileFieldsContent');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
?>

<?php include APPLICATION_PATH . '/modules/user/views/regist/navi.php'; ?>

<form id="registForm" name="registForm" method="POST">
    <?=$this->getTokenField() ?>
    <div class="regist card panel-default">
        <div class="card-header">
            <?=$this->getTrans('logindata') ?>
        </div>
        <div class="card-body">
            <div class="row mb-3 d-none">
                <label class="col-xl-2 col-form-label">
                    <?=$this->getTrans('bot') ?>*
                </label>
                <div class="col-xl-8">
                    <input type="text"
                           class="form-control"
                           name="bot"
                           placeholder="Bot" />
                </div>
            </div>
            <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
                <label for="name" class="col-form-label col-xl-2">
                    <?=$this->getTrans('name') ?>:
                </label>
                <div class="col-xl-8">
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           value="<?= $this->originalInput('name') ?>"
                           autocomplete="username" />
                </div>
            </div>
            <div class="row mb-3<?=$this->validation()->hasError('password') ? ' has-error' : '' ?>">
                <label for="password" class="col-form-label col-xl-2">
                    <?=$this->getTrans('password') ?>:
                </label>
                <div class="col-xl-8">
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           value="<?= $this->originalInput('password') ?>"
                           autocomplete="new-password" />
                </div>
            </div>
            <div class="row mb-3<?=$this->validation()->hasError('password2') ? ' has-error' : '' ?>">
                <label for="password2" class="col-form-label col-xl-2">
                    <?=$this->getTrans('password2') ?>:
                </label>
                <div class="col-xl-8">
                    <input type="password"
                           class="form-control"
                           id="password2"
                           name="password2"
                           value="<?= $this->originalInput('password2') ?>"
                           autocomplete="new-password" />
                </div>
            </div>
            <div class="row mb-3<?=$this->validation()->hasError('email') ? ' has-error' : '' ?>">
                <label for="email" class="col-form-label col-xl-2">
                    <?=$this->getTrans('emailAdress') ?>:
                </label>
                <div class="col-xl-8">
                    <input type="text"
                           class="form-control"
                           id="email"
                           name="email"
                           value="<?= $this->originalInput('email') ?>" />
                </div>
            </div>
            <?php foreach ($profileFields as $profileField) :
                $profileFieldName = $profileField->getKey();
                foreach ($profileFieldsTranslation as $profileFieldTranslation) {
                    if ($profileField->getId() == $profileFieldTranslation->getFieldId()) {
                        $profileFieldName = $profileFieldTranslation->getName();
                        break;
                    }
                }
                if ($profileField->getType() != 1) :
                    $value = ($profileField->getType() == 4) ? [] : '';
                    $index = 'profileField' . $profileField->getId();
                    $value = $this->escape($this->originalInput($index));
                    ?>
                    <div class="row mb-3">
                        <label class="col-xl-2 col-form-label" for="<?=$index ?>">
                            <?=$this->escape($profileFieldName) ?><?=($profileField->getRegistration() === 2) ? ' *' : '' ?>
                        </label>
                        <div class="col-xl-8">
                        <!-- radio -->
                        <?php if ($profileField->getType() == 3) :
                            $options = json_decode($profileField->getOptions(), true);
                            foreach ($options as $optValue) : ?>
                                <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '<div class="form-check">' ?>
                                    <input type="radio" name="<?=$index ?>" id="<?=$optValue ?>" value="<?=$optValue ?>" class="form-check-input" <?=($optValue == $value) ? 'checked' : '' ?> <?= ($profileField->getRegistration() === 2) ? 'required' : '' ?> />
                                    <label class="form-check-label" for="<?=$optValue ?>"><?=$this->escape($optValue) ?></label>
                                    <?php if ($profileField->getShow() == 0) : ?>
                                        <span class="input-group-text check" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                            <span class="fa-solid fa-eye-slash"></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <!-- check -->
                        <?php elseif ($profileField->getType() == 4) :
                            $options = json_decode($profileField->getOptions(), true);
                            foreach ($options as $optKey => $optValue) : ?>
                                <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '<div class="form-check">' ?>
                                    <input type="checkbox" name="<?=$index ?>[<?=$optKey ?>]" id="<?=$optValue ?>" value="<?=$optValue ?>" class="form-check-input" <?=in_array($optValue, $value) ? 'checked' : '' ?> <?= ($profileField->getRegistration() === 2) ? 'required' : '' ?> />
                                    <label class="form-check-label" for="<?=$optValue ?>"><?=$this->escape($optValue) ?></label>
                                    <?php if ($profileField->getShow() == 0) : ?>
                                        <span class="input-group-text check" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                            <span class="fa-solid fa-eye-slash"></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <!-- drop -->
                        <?php elseif ($profileField->getType() == 5) :
                            $options = json_decode($profileField->getOptions(), true);?>
                            <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '<div class="form-check">' ?>
                                <select class="form-select" id="<?=$index ?>" name="<?=$index ?>"<?= ($profileField->getRegistration() === 2) ? 'required' : '' ?>>
                                    <?php foreach ($options as $optValue) : ?>
                                        <option value="<?=$optValue ?>" <?=($optValue == $value) ? 'selected' : '' ?>><?=$this->escape($optValue) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php if ($profileField->getShow() == 0) : ?>
                                <span class="input-group-text" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                    <span class="fa-solid fa-eye-slash"></span>
                                </span>
                            <?php endif; ?>
                            </div>
                        <!-- date -->
                        <?php elseif ($profileField->getType() == 6) : ?>
                            <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '' ?>
                                <input type="text"
                                       class="form-control ilch-date date form_datetime"
                                       name="<?=$index ?>"
                                       id="<?=$index ?>"
                                       placeholder="<?=$value ?>"
                                       value="<?=$value ?>"
                                       <?= ($profileField->getRegistration() === 2) ? 'required' : '' ?> />
                            <?php if ($profileField->getShow() == 0) : ?>
                                <span class="input-group-text" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                    <span class="fa-solid fa-eye-slash"></span>
                                </span>
                            </div>
                            <?php endif; ?>
                        <!-- field -->
                        <?php else : ?>
                            <?=($profileField->getShow() == 0) ? '<div class="input-group">' : '' ?>
                            <input type="text"
                                   class="form-control"
                                   name="<?=$index ?>"
                                   id="<?=$index ?>"
                                   placeholder="<?=$value ?>"
                                   value="<?=$value ?>"
                                   <?= ($profileField->getRegistration() === 2) ? 'required' : '' ?> />
                            <?php if ($profileField->getShow() == 0) : ?>
                                <span class="input-group-text" rel="tooltip" title="<?=$this->getTrans('profileFieldHidden') ?>">
                                    <span class="fa-solid fa-eye-slash"></span>
                                </span>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <h1><?=$this->escape($profileFieldName) ?></h1>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')) : ?>
                <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
            <?php endif; ?>
        </div>
        <div class="panel-footer clearfix">
            <div class="float-end">
                <?php
                    if ($this->get('captchaNeeded')) {
                        if ($this->get('googlecaptcha')) {
                            echo $this->get('googlecaptcha')->setForm('registForm')->getCaptcha($this, 'registButton', 'Regist');
                        } else {
                            echo $this->getSaveBar('registButton', 'Regist');
                        }
                    } else {
                        echo $this->getSaveBar('registButton', 'Regist');
                    }
                ?>
            </div>
        </div>
    </div>
</form>

<script src="<?=$this->getStaticUrl('../application/modules/user/static/js/pStrength.jquery.js') ?>"></script>
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
