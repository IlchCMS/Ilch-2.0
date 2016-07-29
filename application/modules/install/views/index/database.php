<?php $errors = $this->get('errors'); ?>

<div class="form-group <?php if (!empty($errors['dbDatabase'])) { echo 'has-error'; }; ?>">
    <label for="dbName" class="col-lg-3 control-label">
        <?=$this->getTrans('dbName') ?>:
    </label>
    <div class="col-lg-9">
        <select class="form-control" name="dbName" id="dbName">
            <option value=""><?=$this->getTrans('selectDatabase') ?></option>
            <?php foreach ($this->get('database') as $value): ?>
                <?php $selected = ''; ?>
                <?php if ($this->get('dbName') == $value): ?>
                    <?php $selected = 'selected="selected"'; ?>
                <?php endif; ?>

                <option <?=$selected ?> value="<?=$value ?>"><?=$this->escape($value) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (!empty($errors['dbDatabase'])): ?>
        <span class="col-lg-offset-3 col-lg-9 help-block"><?=$this->getTrans($errors['dbDatabase']) ?></span>
    <?php endif; ?>
</div>
<div class="form-group">
    <label for="dbPrefix" class="col-lg-3 control-label">
        <?=$this->getTrans('dbPrefix') ?>:
    </label>
    <div class="col-lg-9">
        <input value="<?php if ($this->get('dbPrefix') != '') { echo $this->escape($this->get('dbPrefix')); } else { echo 'ilch_'; } ?>"
               type="text"
               class="form-control"
               name="dbPrefix"
               id="dbPrefix" />
    </div>
</div>
