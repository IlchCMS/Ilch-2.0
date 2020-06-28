<?php $errors = $this->get('errors'); ?>

<div class="form-group">
    <label for="dbEngine" class="col-lg-3 control-label">
        <?=$this->getTrans('dbEngine') ?>:
    </label>
    <div class="col-lg-9">
        <select class="form-control" id="dbEngine" name="dbEngine">
            <option value="Mysql">Mysql</option>
        </select>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; } ?>">
    <label for="dbHost" class="col-lg-3 control-label">
        <?=$this->getTrans('dbHost') ?>:
    </label>
    <div class="col-lg-9 input-group">
        <input type="text"
               class="form-control"
               id="dbHost"
               name="dbHost"
               value="<?php if ($this->get('dbHost') != '') { echo $this->escape($this->get('dbHost')); } else { echo 'localhost'; } ?>" />
        <div class="input-group-addon" rel="tooltip" title="<?=$this->getTrans('dbHostInfo') ?>"><i class="fa fa-info-circle"></i></div>
    </div>
    <?php if (!empty($errors['dbConnection'])): ?>
        <span class="col-lg-offset-3 col-lg-9 help-block"><?=$this->getTrans($errors['dbConnection']) ?></span>
    <?php endif; ?>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection']) OR !empty($errors['dbUser'])) { echo 'has-error'; } ?>">
    <label for="dbUser" class="col-lg-3 control-label">
        <?=$this->getTrans('dbUser') ?>:
    </label>
    <div class="col-lg-9 input-group">
        <input type="text"
               class="form-control"
               id="dbUser"
               name="dbUser"
               value="<?php if ($this->get('dbUser') != '') { echo $this->escape($this->get('dbUser')); } ?>" />
        <div class="input-group-addon" rel="tooltip" title="<?=$this->getTrans('dbUserInfo') ?>"><i class="fa fa-info-circle"></i></div>
    </div>
    <?php if (!empty($errors['dbUser'])): ?>
        <span class="col-lg-offset-3 col-lg-9 help-block"><?=$this->getTrans($errors['dbUser']) ?></span>
    <?php endif; ?>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; } ?>">
    <label for="dbPassword" class="col-lg-3 control-label">
        <?=$this->getTrans('dbPassword') ?>:
    </label>
    <div class="col-lg-9">
        <input type="password"
               class="form-control"
               id="dbPassword"
               name="dbPassword"
               value="<?php if ($this->get('dbPassword') != '') { echo $this->escape($this->get('dbPassword')); } ?>" />
    </div>
</div>

<script>
$(function () {
    $("[rel='tooltip']").tooltip({
        'placement': 'bottom',
        'container': 'body'
    });
});
</script>
