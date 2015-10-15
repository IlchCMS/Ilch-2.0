<?php
$errors = $this->get('errors');
?>

<div class="form-group">
    <label for="usage" class="control-label col-lg-2">
        <?=$this->getTrans('usage') ?>:
    </label>
    <div class="col-lg-8">
        <select name="usage" id="usage" class="form-control">
            <option value="private"><?=$this->getTrans('private') ?></option>
            <option <?php if ($this->get('usage') == 'clan') { echo 'selected="selected"'; } ?> value="clan"><?=$this->getTrans('clan') ?></option>
        </select>
        <a href="#" data-toggle="collapse" data-target="#modules">
            <?=$this->getTrans('custom')?> &dArr;
        </a>
    </div>
</div>
<div class="form-group collapse" id="modules">
    <div id="modulesContent" class="col-lg-12"></div>
</div>
<hr />
<div class="form-group <?php if (!empty($errors['adminName'])) { echo 'has-error'; }; ?>">
    <label for="adminName" class="control-label col-lg-2">
        <?=$this->getTrans('adminName') ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminName') != '') { echo $this->escape($this->get('adminName')); } ?>"
               type="text"
               name="adminName"
               class="form-control"
               id="adminName" />
        <?php if (!empty($errors['adminName'])): ?>
            <span class="help-inline"><?=$this->getTrans($errors['adminName']) ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminPassword'])) { echo 'has-error'; }; ?>">
    <label for="adminPassword" class="control-label col-lg-2">
        <?=$this->getTrans('adminPassword') ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminPassword') != '') { echo $this->escape($this->get('adminPassword')); } ?>"
               type="password"
               class="form-control"
               name="adminPassword"
               id="adminPassword" />
        <?php if (!empty($errors['adminPassword'])): ?>
            <span class="help-inline"><?=$this->getTrans($errors['adminPassword']) ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminPassword2'])) { echo 'has-error'; }; ?>">
    <label for="adminPassword2" class="control-label col-lg-2">
        <?=$this->getTrans('adminPassword2') ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminPassword2') != '') { echo $this->escape($this->get('adminPassword2')); } ?>"
               type="password"
               class="form-control"
               name="adminPassword2"
               id="adminPassword2" />
        <?php if (!empty($errors['adminPassword2'])): ?>
            <span class="help-inline"><?=$this->getTrans($errors['adminPassword2']) ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminEmail'])) { echo 'has-error'; }; ?>">
    <label for="adminEmail" class="control-label col-lg-2">
        <?=$this->getTrans('adminEmail') ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminEmail') != '') { echo $this->escape($this->get('adminEmail')); } ?>"
               type="text"
               name="adminEmail"
               class="form-control"
               id="adminEmail" />
        <?php if (!empty($errors['adminEmail'])): ?>
            <span class="help-inline"><?=$this->getTrans($errors['adminEmail']) ?></span>
        <?php endif; ?>
    </div>
</div>

<script src="<?=$this->getStaticUrl('../application/modules/user/static/js/pStrength.jquery.js'); ?>"></script>
<script>
$('#usage').change(function(){
    $('#modulesContent').load('<?=$this->getUrl(array('action' => 'ajaxconfig')) ?>/type/'+$('#usage').val());
});

$('#modulesContent').load('<?=$this->getUrl(array('action' => 'ajaxconfig')) ?>/type/'+$('#usage').val());

$(document).ready(function(){
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
