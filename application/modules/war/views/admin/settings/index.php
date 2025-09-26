<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('warsPerPage') ? ' has-error' : '' ?>">
        <label for="warsPerPageInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('warsPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="warsPerPageInput"
                   name="warsPerPage"
                   min="1"
                   value="<?=$this->originalInput('warsPerPage', $this->get('warsPerPage'), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('enemiesPerPage') ? ' has-error' : '' ?>">
        <label for="enemiesPerPageInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('enemiesPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="enemiesPerPageInput"
                   name="enemiesPerPage"
                   min="1"
                   value="<?=$this->originalInput('enemiesPerPage', $this->get('enemiesPerPage'), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('groupsPerPage') ? ' has-error' : '' ?>">
        <label for="groupsPerPageInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('groupsPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="groupsPerPageInput"
                   name="groupsPerPage"
                   min="1"
                   value="<?=$this->originalInput('groupsPerPage', $this->get('groupsPerPage'), true) ?>" />
        </div>
    </div>

    <h1><?=$this->getTrans('boxSettings') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('boxNextWarLimit') ? ' has-error' : '' ?>">
        <label for="limitNextWarInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('nextWarLimit') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="limitNextWarInput"
                   name="boxNextWarLimit"
                   min="1"
                   value="<?=$this->originalInput('boxNextWarLimit', $this->get('boxNextWarLimit'), true) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('boxLastWarLimit') ? ' has-error' : '' ?>">
        <label for="limitLastWarInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('lastWarLimit') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="limitLastWarInput"
                   name="boxLastWarLimit"
                   min="1"
                   value="<?=$this->originalInput('boxLastWarLimit', $this->get('boxLastWarLimit'), true) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
