<?php

/** @var \Ilch\View $this */
?>
<div class="mb-3 <?=$this->validation()->hasError('dbEngine') ? 'has-error' : '' ?>">
    <label for="dbEngine" class="col-lg-3 form-label">
        <?=$this->getTrans('dbEngine') ?>:
    </label>
    <div class="col-g-9">
        <select class="form-select" id="dbEngine" name="dbEngine">
            <option <?=($this->originalInput('dbEngine', $this->get('dbEngine')) == 'Mysql') ? 'selected="selected"' : '' ?> value="Mysql">Mysql</option>
        </select>
    </div>
</div>
<div class="mb-3 <?=$this->validation()->hasError('dbHost') ? 'has-error' : '' ?>">
    <label for="dbHost" class="col-lg-3 form-label">
        <?=$this->getTrans('dbHost') ?>:
    </label>
    <div class="input-group mb-3">
        <input type="text"
               class="form-control"
               id="dbHost"
               name="dbHost"
               value="<?=$this->escape($this->originalInput('dbHost', $this->get('dbHost'))) ?>" />
        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$this->getTrans('dbHostInfo') ?>"><i class="fa-solid fa-circle-info"></i></div>
    </div>
</div>
<div class="mb-3 <?=$this->validation()->hasError('dbUser') ? 'has-error' : '' ?>">
    <label for="dbUser" class="col-lg-3 form-label">
        <?=$this->getTrans('dbUser') ?>:
    </label>
    <div class="input-group mb-3">
        <input type="text"
               class="form-control"
               id="dbUser"
               name="dbUser"
               value="<?=$this->escape($this->originalInput('dbUser', $this->get('dbUser'))) ?>" />
        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$this->getTrans('dbUserInfo') ?>" ><i class="fa-solid fa-circle-info"></i></div>
    </div>
</div>
<div class="mb-3 <?=$this->validation()->hasError('dbPassword') ? 'has-error' : '' ?>">
    <label for="dbPassword" class="col-lg-3 form-label">
        <?=$this->getTrans('dbPassword') ?>:
    </label>
    <div class="col-g-9">
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
