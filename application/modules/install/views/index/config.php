<?php
$errors = $this->get('errors');
?>
<div class="form-group">
    <label for="type" class="control-label col-lg-3">
        <?php echo $this->trans('cmsType'); ?>:
    </label>
    <div class="col-lg-6">
        <select name="cmsType" class="form-control">
            <option value="private">Private</option>
            <option value="clan">Clan</option>
        </select>
    </div>
</div>
<hr />
<div class="form-group <?php if (!empty($errors['adminName'])) { echo 'has-error'; }; ?>">
    <label for="adminName" class="control-label col-lg-3">
        <?php echo $this->trans('adminName'); ?>:
    </label>
    <div class="col-lg-6">
        <input value="<?php if ($this->get('adminName') != '') { echo $this->escape($this->get('adminName')); } ?>"
               type="text"
               name="adminName"
               class="form-control"
               id="adminName" />
        <?php
            if (!empty($errors['adminName'])) {
                echo '<span class="help-inline">'.$this->trans($errors['adminName']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminPassword'])) { echo 'has-error'; }; ?>">
    <label for="adminPassword" class="control-label col-lg-3">
        <?php echo $this->trans('adminPassword'); ?>:
    </label>
    <div class="col-lg-6">
        <input value="<?php if ($this->get('adminPassword') != '') { echo $this->escape($this->get('adminPassword')); } ?>"
               type="password"
               class="form-control"
               name="adminPassword"
               id="adminPassword" />
        <?php
            if (!empty($errors['adminPassword'])) {
                echo '<span class="help-inline">'.$this->trans($errors['adminPassword']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminPassword2'])) { echo 'has-error'; }; ?>">
    <label for="adminPassword2" class="control-label col-lg-3">
        <?php echo $this->trans('adminPassword2'); ?>:
    </label>
    <div class="col-lg-6">
        <input value="<?php if ($this->get('adminPassword2') != '') { echo $this->escape($this->get('adminPassword2')); } ?>"
               type="password"
               class="form-control"
               name="adminPassword2"
               id="adminPassword2" />
        <?php
            if (!empty($errors['adminPassword2'])) {
                echo '<span class="help-inline">'.$this->trans($errors['adminPassword2']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminEmail'])) { echo 'has-error'; }; ?>">
    <label for="adminEmail" class="control-label col-lg-3">
        <?php echo $this->trans('adminEmail'); ?>:
    </label>
    <div class="col-lg-6">
        <input value="<?php if ($this->get('adminEmail') != '') { echo $this->escape($this->get('adminEmail')); } ?>"
               type="text"
               name="adminEmail"
               class="form-control"
               id="adminEmail" />
        <?php
            if (!empty($errors['adminEmail'])) {
                echo '<span class="help-inline">'.$this->trans($errors['adminEmail']).'</span>';
            }
        ?>
    </div>
</div>
