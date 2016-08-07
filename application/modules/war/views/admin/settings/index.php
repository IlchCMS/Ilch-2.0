<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="warsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('warsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="warsPerPageInput"
                   name="warsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('warsPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemiesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemiesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="enemiesPerPageInput"
                   name="enemiesPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('enemiesPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="groupsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="groupsPerPageInput"
                   name="groupsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('groupsPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
