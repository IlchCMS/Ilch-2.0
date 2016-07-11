<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('settings') ?></legend>
    <div class="form-group">
        <label for="warsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('warsPerPage') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="warsPerPageInput"
                   name="warsPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('warsPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="enemiesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemiesPerPage') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="enemiesPerPageInput"
                   name="enemiesPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('enemiesPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="groupsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupsPerPage') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="groupsPerPageInput"
                   name="groupsPerPage"
                   type="number"
                   min="1"
                   value="<?=$this->escape($this->get('groupsPerPage')) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
