<?php

/** @var \Ilch\View $this */

/** @var array $database */
$database = $this->get('database')
?>
<div class="row mb-3">
    <label for="dbName" class="col-xl-3 form-label <?=$this->validation()->hasError('dbName') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('dbName') ?>:
    </label>
    <?php if (count($database) > 0) : ?>
        <div class="col-xl-9">
            <select class="form-select <?=$this->validation()->hasError('dbName') ? 'is-invalid' : '' ?>" id="dbName" name="dbName">
                <option value=""><?=$this->getTrans('selectDatabase') ?></option>
                <?php foreach ($database as $value) : ?>
                    <option <?=$this->originalInput('dbName', $this->get('dbName')) == $value ? 'selected="selected"' : '' ?> value="<?=$value ?>"><?=$this->escape($value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else : ?>
        <div class="col-lg-9 input-group">
            <input type="text"
                   class="form-control <?=$this->validation()->hasError('dbName') ? 'is-invalid' : '' ?>"
                   id="dbName"
                   name="dbName"
                   value="<?=$this->escape($this->originalInput('dbName', $this->get('dbName'))) ?>" />
            <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$this->getTrans('dbNameInfo') ?>"><i class="fa-solid fa-circle-info"></i></div>
        </div>
    <?php endif; ?>
</div>
<div class="row mb-3">
    <label for="dbPrefix" class="col-xl-3 form-label <?=$this->validation()->hasError('dbPrefix') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('dbPrefix') ?>:
    </label>
    <div class="col-xl-9 input-group">
        <input type="text"
               class="form-control <?=$this->validation()->hasError('dbPrefix') ? 'is-invalid' : '' ?>"
               id="dbPrefix"
               name="dbPrefix"
               value="<?=$this->escape($this->originalInput('dbPrefix', $this->get('dbPrefix'))) ?>" />
        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$this->getTrans('dbPrefixInfo') ?>"><i class="fa-solid fa-circle-info"></i></div>
    </div>
</div>
