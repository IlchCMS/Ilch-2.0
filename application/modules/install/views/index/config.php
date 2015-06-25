<?php
$errors = $this->get('errors');
?>
<div class="form-group">
    <label for="usage" class="control-label col-lg-2">
        <?php echo $this->getTrans('usage'); ?>:
    </label>
    <div class="col-lg-8">
        <select name="usage" id="usage" class="form-control">
            <option value="private"><?=$this->getTrans('private')?></option>
            <option <?php if ($this->get('usage') == 'clan') { echo 'selected="selected"'; } ?> value="clan"><?=$this->getTrans('clan')?></option>
        </select>
        <a href="#" data-toggle="collapse" data-target="#modules">
          <?=$this->getTrans('custom')?> &dArr;
        </a>
    </div>
</div>
<div class="form-group collapse" id="modules">
    <div id="modulesContent" class="col-lg-12"></div>
</div>
<hr />
<div class="form-group <?php if (!empty($errors['adminName'])) { echo 'has-error'; }; ?>">
    <label for="adminName" class="control-label col-lg-2">
        <?php echo $this->getTrans('adminName'); ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminName') != '') { echo $this->escape($this->get('adminName')); } ?>"
               type="text"
               name="adminName"
               class="form-control"
               id="adminName" />
        <?php
            if (!empty($errors['adminName'])) {
                echo '<span class="help-inline">'.$this->getTrans($errors['adminName']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminPassword'])) { echo 'has-error'; }; ?>">
    <label for="adminPassword" class="control-label col-lg-2">
        <?php echo $this->getTrans('adminPassword'); ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminPassword') != '') { echo $this->escape($this->get('adminPassword')); } ?>"
               type="password"
               class="form-control"
               name="adminPassword"
               id="adminPassword" />
        <?php
            if (!empty($errors['adminPassword'])) {
                echo '<span class="help-inline">'.$this->getTrans($errors['adminPassword']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminPassword2'])) { echo 'has-error'; }; ?>">
    <label for="adminPassword2" class="control-label col-lg-2">
        <?php echo $this->getTrans('adminPassword2'); ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminPassword2') != '') { echo $this->escape($this->get('adminPassword2')); } ?>"
               type="password"
               class="form-control"
               name="adminPassword2"
               id="adminPassword2" />
        <?php
            if (!empty($errors['adminPassword2'])) {
                echo '<span class="help-inline">'.$this->getTrans($errors['adminPassword2']).'</span>';
            }
        ?>
    </div>
</div>
<div class="form-group <?php if (!empty($errors['adminEmail'])) { echo 'has-error'; }; ?>">
    <label for="adminEmail" class="control-label col-lg-2">
        <?php echo $this->getTrans('adminEmail'); ?>:
    </label>
    <div class="col-lg-8">
        <input value="<?php if ($this->get('adminEmail') != '') { echo $this->escape($this->get('adminEmail')); } ?>"
               type="text"
               name="adminEmail"
               class="form-control"
               id="adminEmail" />
        <?php
            if (!empty($errors['adminEmail'])) {
                echo '<span class="help-inline">'.$this->getTrans($errors['adminEmail']).'</span>';
            }
        ?>
    </div>
</div>
<script>
    $('#usage').change(function(){
        $('#modulesContent').load('<?=$this->getUrl(array('action' => 'ajaxconfig'))?>/type/'+$('#usage').val());
    });

    $('#modulesContent').load('<?=$this->getUrl(array('action' => 'ajaxconfig'))?>/type/'+$('#usage').val());
</script>