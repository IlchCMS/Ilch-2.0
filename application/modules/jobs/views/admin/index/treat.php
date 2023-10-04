<?php

/** @var \Ilch\View $this */

/** @var \Modules\Jobs\Models\Jobs $job */
$job = $this->get('job');
?>
<h1>
    <?=$this->getTrans($job->getId() ? 'edit' : 'add') ?>
</h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('show') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('show') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="jobs-yes" name="show" value="1" <?=$this->originalInput('show', $job->getShow()) ? 'checked="checked"' : '' ?> />
                <label for="jobs-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="jobs-no" name="show" value="0" <?=!$this->originalInput('show', $job->getShow()) ? 'checked="checked"' : '' ?> />
                <label for="jobs-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $job->getTitle())) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->originalInput('text', $job->getText())) ?></textarea>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <label for="email" class="col-lg-2 control-label">
            <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-lg-4">
            <input type="email"
                   class="form-control"
                   id="email"
                   name="email"
                   value="<?=$this->escape($this->originalInput('email', $job->getEmail())) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar($job->getId() ? 'updateButton' : 'addButton') ?>
</form>
