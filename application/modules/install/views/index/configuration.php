<?php

/** @var \Ilch\View $this */

/** @var array $errors */
$errors = $this->get('errors');

/** @var array $usages */
$usages = $this->get('usages');
?>
<div class="form-group <?=$this->validation()->hasError('usage') ? 'has-error' : '' ?>">
    <label for="usage" class="control-label col-lg-3">
        <?=$this->getTrans('usage') ?>:
    </label>
    <div class="col-lg-9 input-group">
        <select class="form-control" id="usage" name="usage">
            <?php foreach ($usages as $value) : ?>
                <option <?=$this->originalInput('usage', $this->get('usage')) == $value ? 'selected="selected"' : '' ?> value="<?=$value ?>"><?=$this->getTrans($value) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="input-group-addon custom" data-toggle="collapse" data-target="#modules" title="<?=$this->getTrans('custom') ?>"><i class="fa-solid fa-circle-info"></i> <?=$this->getTrans('custom') ?></div>
    </div>
</div>

<div class="form-group collapse" id="modules">
    <div id="modulesContent" class="col-lg-offset-1 col-lg-11"></div>
</div>

<div class="form-group <?=$this->validation()->hasError('adminName') ? 'has-error' : '' ?>">
    <label for="adminName" class="control-label col-lg-3">
        <?=$this->getTrans('adminName') ?>:
    </label>
    <div class="col-lg-9">
        <input type="text"
               class="form-control"
               id="adminName"
               name="adminName"
               value="<?=$this->escape($this->originalInput('adminName', $this->get('adminName'))) ?>"
               autocomplete="username" />
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('adminPassword') ? 'has-error' : '' ?>">
    <label for="adminPassword" class="control-label col-lg-3">
        <?=$this->getTrans('adminPassword') ?>:
    </label>
    <div class="col-lg-9">
        <input type="password"
               class="form-control"
               id="adminPassword"
               name="adminPassword"
               value="<?=$this->escape($this->originalInput('adminPassword', $this->get('adminPassword'))) ?>"
               autocomplete="new-password" />
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('adminPassword2') ? 'has-error' : '' ?>">
    <label for="adminPassword2" class="control-label col-lg-3">
        <?=$this->getTrans('adminPassword2') ?>:
    </label>
    <div class="col-lg-9">
        <input type="password"
               class="form-control"
               id="adminPassword2"
               name="adminPassword2"
               value="<?=$this->escape($this->originalInput('adminPassword2', $this->get('adminPassword2'))) ?>"
               autocomplete="new-password" />
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('adminEmail') ? 'has-error' : '' ?>">
    <label for="adminEmail" class="control-label col-lg-3">
        <?=$this->getTrans('adminEmail') ?>:
    </label>
    <div class="col-lg-9">
        <input type="text"
               class="form-control"
               id="adminEmail"
               name="adminEmail"
               value="<?=$this->escape($this->originalInput('adminEmail', $this->get('adminEmail'))) ?>" />
    </div>
</div>

<script src="<?=$this->getStaticUrl('../application/modules/user/static/js/pStrength.jquery.js') ?>"></script>
<script>
    $('#usage').change(function() {
        $('#modulesContent').load('<?=$this->getUrl(['action' => 'ajaxconfig']) ?>/type/'+$('#usage').val()+'/reload/true');
    });

    $('#modulesContent').load('<?=$this->getUrl(['action' => 'ajaxconfig']) ?>/type/'+$('#usage').val());

    $(document).ready(function() {
        $('#adminPassword').pStrength({
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
