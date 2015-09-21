<?php
$jobs = $this->get('jobs');
?>

<legend>
    <?php if ($this->get('jobs') != ''): ?>
        <?=$this->getTrans('edit') ?>
    <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
</legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('show') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="show" value="1" id="jobs-yes"
                    <?php if ($this->get('jobs') != ''): ?>
                        <?php if ($this->get('jobs')->getShow() == 1): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="jobs-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" name="show" value="0" id="jobs-no"
                    <?php if ($this->get('jobs') != '' AND $this->get('jobs')->getShow() == 0): ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="jobs-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   value="<?php if ($this->get('jobs') != '') { echo $this->escape($this->get('jobs')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                   name="text"
                   id="ck_1"
                   toolbar="ilch_html"
                   rows="5"><?php if ($this->get('jobs') != '') { echo $this->escape($this->get('jobs')->getText()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-2 control-label">
            <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="email"
                   value="<?php if ($this->get('jobs') != '') { echo $this->escape($this->get('jobs')->getEmail()); } ?>" />
        </div>
    </div>
    <?php if ($this->get('jobs') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>
