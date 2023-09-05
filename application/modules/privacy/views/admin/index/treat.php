<?php

/** @var \Ilch\View $this */

/** @var \Modules\Privacy\Models\Privacy $privacy */
$privacy = $this->get('privacy');
?>
<h1>
    <?=($privacy->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?>
</h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('show') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('show') ?>
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="show-on" name="show" value="1" <?=($this->originalInput('show', $privacy->getShow())) ? 'checked="checked"' : '' ?> />
                <label for="show-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="show-off" name="show" value="0" <?=(!$this->originalInput('show', $privacy->getShow())) ? 'checked="checked"' : '' ?> />
                <label for="show-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $privacy->getTitle())) ?>" />
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?=$this->originalInput('text', $privacy->getText()) ?></textarea>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="urltitle" class="col-lg-2 control-label">
            <?=$this->getTrans('urlTitle') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="urltitle"
                   name="urltitle"
                   value="<?=$this->escape($this->originalInput('urltitle', $privacy->getUrlTitle())) ?>" />
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('url') ? 'has-error' : '' ?>">
        <label for="url" class="col-lg-2 control-label">
            <?=$this->getTrans('url') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="url"
                   name="url"
                   placeholder="https://"
                   value="<?=$this->escape($this->originalInput('url', $privacy->getUrl())) ?>" />
        </div>
    </div>
    <?=($privacy) ? $this->getSaveBar('edit') : $this->getSaveBar('add') ?>
</form>
