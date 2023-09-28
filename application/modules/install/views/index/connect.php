<?php

/** @var \Ilch\View $this */
?>
<div class="row mb-3">
    <label for="dbEngine" class="col-xl-3 form-label <?=$this->validation()->hasError('dbEngine') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('dbEngine') ?>:
    </label>
    <div class="col-xl-9">
        <select class="form-select <?=$this->validation()->hasError('dbEngine') ? 'is-invalid' : '' ?>" id="dbEngine" name="dbEngine">
            <option <?=($this->originalInput('dbEngine', $this->get('dbEngine')) == 'Mysql') ? 'selected="selected"' : '' ?> value="Mysql">Mysql</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="dbHost" class="col-xl-3 form-label <?=$this->validation()->hasError('dbHost') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('dbHost') ?>:
    </label>
    <div class="col-xl-9">
        <div class="input-group">
            <input type="text"
               class="form-control <?=$this->validation()->hasError('dbHost') ? 'is-invalid' : '' ?>"
               id="dbHost"
               name="dbHost"
               value="<?=$this->escape($this->originalInput('dbHost', $this->get('dbHost'))) ?>" />
            <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$this->getTrans('dbHostInfo') ?>"><i class="fa-solid fa-circle-info"></i></div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <label for="dbUser" class="col-xl-3 form-label <?=$this->validation()->hasError('dbUser') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('dbUser') ?>:
    </label>
    <div class="col-xl-9">
        <div class="input-group">
            <input type="text"
               class="form-control <?=$this->validation()->hasError('dbUser') ? 'is-invalid' : '' ?>"
               id="dbUser"
               name="dbUser"
               value="<?=$this->escape($this->originalInput('dbUser', $this->get('dbUser'))) ?>" />
            <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$this->getTrans('dbUserInfo') ?>" ><i class="fa-solid fa-circle-info"></i></div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <label for="dbPassword" class="col-xl-3 form-label <?=$this->validation()->hasError('dbPassword') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('dbPassword') ?>:
    </label>
    <div class="col-xl-9">
        <input type="password"
               class="form-control <?=$this->validation()->hasError('dbPassword') ? 'is-invalid' : '' ?>"
               id="dbPassword"
               name="dbPassword"
               value="<?=$this->escape($this->originalInput('dbPassword', $this->get('dbPassword'))) ?>" />
    </div>
</div>

<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
