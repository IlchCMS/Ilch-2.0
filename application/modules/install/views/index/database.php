<?php
$errors = $this->get('errors');
?>
<div class="form-group">
    <label for="dbEngine" class="col-xs-2 control-label">
        <?php echo $this->trans('dbEngine'); ?>:
    </label>
    <div class="col-xs-6">
        <select name="dbEngine" class="form-control" id="dbEngine">
            <option value="Mysql">Mysql</option>
        </select>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; }; ?>">
    <label for="dbHost" class="col-xs-2 control-label">
        <?php echo $this->trans('dbHost'); ?>:
    </label>
    <div class="col-xs-6">
        <input value="<?php if ($this->get('dbHost') != '') { echo $this->escape($this->get('dbHost')); } else { echo 'localhost'; } ?>"
               type="text"
               class="form-control"
               name="dbHost"
               id="dbHost" />
        <?php
            if (!empty($errors['dbConnection'])) {
                echo '<span class="help-block">'.$this->trans($errors['dbConnection']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; }; ?>">
    <label for="dbUser" class="col-xs-2 control-label">
        <?php echo $this->trans('dbUser'); ?>:
    </label>
    <div class="col-xs-6">
        <input value="<?php if ($this->get('dbUser') != '') { echo $this->escape($this->get('dbUser')); } ?>"
               type="text"
               class="form-control"
               name="dbUser"
               id="dbUser" />
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; }; ?>">
    <label for="dbPassword" class="col-xs-2 control-label">
        <?php echo $this->trans('dbPassword'); ?>:
    </label>
    <div class="col-xs-6">
        <input value="<?php if ($this->get('dbPassword') != '') { echo $this->escape($this->get('dbPassword')); } ?>"
               type="password"
               class="form-control"
               name="dbPassword"
               id="dbPassword" />
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbDatabase'])) { echo 'has-error'; }; ?>">
    <label for="dbName" class="col-xs-2 control-label">
        <?php echo $this->trans('dbName'); ?>:
    </label>
    <div class="col-xs-6">
        <input value="<?php if ($this->get('dbName') != '') { echo $this->escape($this->get('dbName')); } ?>"
               type="text"
               name="dbName"
               class="form-control"
               id="dbName" />
        <?php
            if (!empty($errors['dbDatabase'])) {
                echo '<span class="help-block">'.$this->trans($errors['dbDatabase']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group">
    <label for="dbPrefix" class="col-xs-2 control-label">
        <?php echo $this->trans('dbPrefix'); ?>:
    </label>
    <div class="col-xs-6">
        <input value="<?php if ($this->get('dbPrefix') != '') { echo $this->escape($this->get('dbPrefix')); } else { echo 'ilch_'; } ?>"
               type="text"
               class="form-control"
               name="dbPrefix"
               id="dbPrefix" />
    </div>
</div>
