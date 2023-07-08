<?php

/** @var \Ilch\View $this */

/** @var array $database */
$database = $this->get('database')
?>
<div class="form-group <?=$this->validation()->hasError('dbName') ? 'has-error' : '' ?>">
    <label for="dbName" class="col-lg-3 control-label">
        <?=$this->getTrans('dbName') ?>:
    </label>
    <div class="col-lg-9">
        <?php if (count($database) > 0) : ?>
            <select class="form-control" id="dbName" name="dbName">
                <option value=""><?=$this->getTrans('selectDatabase') ?></option>
                <?php foreach ($database as $value) : ?>
                    <option <?=$this->originalInput('dbName', $this->get('dbName')) == $value ? 'selected="selected"' : '' ?> value="<?=$value ?>"><?=$this->escape($value) ?></option>
                <?php endforeach; ?>
            </select>
        <?php else : ?>
            <input type="text"
                   class="form-control"
                   id="dbName"
                   name="dbName"
                   value="<?=$this->escape($this->originalInput('dbName', $this->get('dbName'))) ?>" />
        <?php endif; ?>
    </div>
</div>
<div class="form-group <?=$this->validation()->hasError('dbPrefix') ? 'has-error' : '' ?>">
    <label for="dbPrefix" class="col-lg-3 control-label">
        <?=$this->getTrans('dbPrefix') ?>:
    </label>
    <div class="col-lg-9">
        <input type="text"
               class="form-control"
               id="dbPrefix"
               name="dbPrefix"
               value="<?=$this->escape($this->originalInput('dbPrefix', $this->get('dbPrefix'))) ?>" />
    </div>
</div>
