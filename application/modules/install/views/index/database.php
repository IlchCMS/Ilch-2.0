<?php
$errors = $this->get('errors');
?>
<div class="form-group">
    <label for="dbEngine" class="col-lg-2 control-label">
        <?php echo $this->getTrans('dbEngine'); ?>:
    </label>
    <div class="col-lg-8">
        <select name="dbEngine" class="form-control" id="dbEngine">
            <option value="Mysql">Mysql</option>
        </select>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; }; ?>">
    <label for="dbHost" class="col-lg-2 control-label">
        <?php echo $this->getTrans('dbHost'); ?>:
    </label>
    <div class="col-lg-8 input-group">
        <input value="<?php if ($this->get('dbHost') != '') { echo $this->escape($this->get('dbHost')); } else { echo 'localhost'; } ?>"
               type="text"
               class="form-control"
               name="dbHost"
               id="dbHost" />
        <?php
            if (!empty($errors['dbConnection'])) {
                echo '<span class="help-block">'.$this->getTrans($errors['dbConnection']).'</span>';
            }
        ?>
        <div class="input-group-addon" rel="tooltip" title="<?php echo $this->getTrans('dbHostInfo'); ?>"><i class="fa fa-info-circle"></i></div>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; }; ?>">
    <label for="dbUser" class="col-lg-2 control-label">
        <?php echo $this->getTrans('dbUser'); ?>:
    </label>
    <div class="col-lg-8 input-group">
        <input value="<?php if ($this->get('dbUser') != '') { echo $this->escape($this->get('dbUser')); } ?>"
               type="text"
               class="form-control"
               name="dbUser"
               id="dbUser" />
        <div class="input-group-addon" rel="tooltip" title="<?php echo $this->getTrans('dbUserInfo'); ?>"><i class="fa fa-info-circle"></i></div>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbConnection'])) { echo 'has-error'; }; ?>">
    <label for="dbPassword" class="col-lg-2 control-label">
        <?php echo $this->getTrans('dbPassword'); ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('dbPassword') != '') { echo $this->escape($this->get('dbPassword')); } ?>"
               type="password"
               class="form-control"
               name="dbPassword"
               id="dbPassword" />
    </div>
</div>
<div class="form-group <?php if (!empty($errors['dbDatabase'])) { echo 'has-error'; }; ?>">
        <label for="dbName" class="col-lg-2 control-label">
            <?php echo $this->getTrans('dbName'); ?>:
        </label>
        <div class="col-lg-8 input-group">
            <input value="<?php if ($this->get('dbName') != '') { echo $this->escape($this->get('dbName')); } ?>"
                   type="text"
                   name="dbName"
                   class="form-control"
                   id="dbName" />
            <?php
                if (!empty($errors['dbDatabase'])) {
                    echo '<span class="help-block">'.$this->getTrans($errors['dbDatabase']).'</span>';
                }
            ?>
            <div class="input-group-addon" rel="tooltip" title="<?php echo $this->getTrans('dbNameInfo'); ?>"><i class="fa fa-info-circle"></i></div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="dbPrefix" class="col-lg-2 control-label">
        <?php echo $this->getTrans('dbPrefix'); ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('dbPrefix') != '') { echo $this->escape($this->get('dbPrefix')); } else { echo 'ilch_'; } ?>"
               type="text"
               class="form-control"
               name="dbPrefix"
               id="dbPrefix" />
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("[rel='tooltip']").tooltip({
            'placement': 'bottom',
            'container': 'body'
        });
    });
</script>
<style>
    .form-group .col-lg-8 {
        padding: 0px;
    }
</style>