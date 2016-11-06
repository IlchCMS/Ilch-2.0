<legend><?=$this->getTrans('settings') ?></legend>

<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('warsPerPage', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="warsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('warsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="warsPerPageInput"
                   name="warsPerPage"
                   min="1"
                   value="<?=(empty($this->get('errorFields'))) ? $this->escape($this->get('warsPerPage')) : $this->get('post')['warsPerPage'] ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('enemiesPerPage', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="enemiesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('enemiesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="enemiesPerPageInput"
                   name="enemiesPerPage"
                   min="1"
                   value="<?=(empty($this->get('errorFields'))) ? $this->escape($this->get('enemiesPerPage')) : $this->get('post')['enemiesPerPage'] ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('groupsPerPage', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="groupsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="groupsPerPageInput"
                   name="groupsPerPage"
                   min="1"
                   value="<?=(empty($this->get('errorFields'))) ? $this->escape($this->get('groupsPerPage')) : $this->get('post')['groupsPerPage'] ?>" />
        </div>
    </div>

    <legend><?=$this->getTrans('boxSettings') ?></legend>
    <div class="form-group <?=in_array('boxNextWarLimit', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="limitNextWarInput" class="col-lg-2 control-label">
            <?=$this->getTrans('nextWarLimit') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="limitNextWarInput"
                   name="boxNextWarLimit"
                   min="1"
                   value="<?=(empty($this->get('errorFields'))) ? $this->escape($this->get('boxNextWarLimit')) : $this->get('post')['boxNextWarLimit'] ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('boxLastWarLimit', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="limitLastWarInput" class="col-lg-2 control-label">
            <?=$this->getTrans('lastWarLimit') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="limitLastWarInput"
                   name="boxLastWarLimit"
                   min="1"
                   value="<?=(empty($this->get('errorFields'))) ? $this->escape($this->get('boxLastWarLimit')) : $this->get('post')['boxLastWarLimit'] ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
