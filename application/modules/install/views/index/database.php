<?php $errors = $this->get('errors'); ?>

<div class="form-group <?php if (!empty($errors['dbDatabase'])) {
    echo 'has-error';
}; ?>">
    <label for="dbName" class="col-lg-3 control-label">
        <?=$this->getTrans('dbName') ?>:
    </label>
    <div class="col-lg-9">
        <?php if (count($this->get('database')) > 0): ?>
            <select class="form-control" id="dbName" name="dbName">
                <option value=""><?=$this->getTrans('selectDatabase') ?></option>
                <?php foreach ($this->get('database') as $value): ?>
                    <?php $selected = ''; ?>
                    <?php if ($this->get('dbName') == $value): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$selected ?> value="<?=$value ?>"><?=$this->escape($value) ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <input type="text"
                   class="form-control"
                   id="dbName"
                   name="dbName"
                   value="<?php if ($this->get('dbName') != '') { echo $this->escape($this->get('dbName')); } ?>" />
        <?php endif; ?>
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
        <input type="text"
               class="form-control"
               id="dbPrefix"
               name="dbPrefix"
               value="<?php if ($this->get('dbPrefix') != '') {
    echo $this->escape($this->get('dbPrefix'));
} else {
    echo 'ilch_';
} ?>" />
    </div>
</div>
