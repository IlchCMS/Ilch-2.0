<?php

/** @var \Ilch\View $this */
?>
<div class="form-group <?=$this->validation()->hasError('dbEngine') ? 'has-error' : '' ?>">
    <label for="dbEngine" class="col-lg-3 control-label">
        <?=$this->getTrans('dbEngine') ?>:
    </label>
    <div class="col-lg-9">
        <select class="form-control" id="dbEngine" name="dbEngine">
            <option <?=($this->originalInput('dbEngine', $this->get('dbEngine')) == 'Mysql') ? 'selected="selected"' : '' ?> value="Mysql">Mysql</option>
        </select>
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('dbHost') ? 'has-error' : '' ?>">
    <label for="dbHost" class="col-lg-3 control-label">
        <?=$this->getTrans('dbHost') ?>:
    </label>
    <div class="col-lg-9 input-group">
        <input type="text"
               class="form-control"
               id="dbHost"
               name="dbHost"
               value="<?=$this->escape($this->originalInput('dbHost', $this->get('dbHost'))) ?>" />
        <div class="input-group-addon" rel="tooltip" title="<?=$this->getTrans('dbHostInfo') ?>"><i class="fa-solid fa-circle-info"></i></div>
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('dbUser') ? 'has-error' : '' ?>">
    <label for="dbUser" class="col-lg-3 control-label">
        <?=$this->getTrans('dbUser') ?>:
    </label>
    <div class="col-lg-9 input-group">
        <input type="text"
               class="form-control"
               id="dbUser"
               name="dbUser"
               value="<?=$this->escape($this->originalInput('dbUser', $this->get('dbUser'))) ?>" />
        <div class="input-group-addon" rel="tooltip" title="<?=$this->getTrans('dbUserInfo') ?>"><i class="fa-solid fa-circle-info"></i></div>
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('dbPassword') ? 'has-error' : '' ?>">
    <label for="dbPassword" class="col-lg-3 control-label">
        <?=$this->getTrans('dbPassword') ?>:
    </label>
    <div class="col-lg-9">
        <input type="password"
               class="form-control"
               id="dbPassword"
               name="dbPassword"
               value="<?=$this->escape($this->originalInput('dbPassword', $this->get('dbPassword'))) ?>" />
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
