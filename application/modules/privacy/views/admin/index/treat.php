<?php $privacy = $this->get('privacy'); ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
    <?php if ($this->get('privacy') != ''): ?>
        <?=$this->getTrans('edit') ?>
    <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('show') ?>:
        </label>
        <div class="col-lg-4">

            <div class="flipswitch">  
                <input type="radio" class="flipswitch-input" name="show" value="1" id="show-on" <?php if ($this->get('privacy') != ''): ?>
                    <?php if ($this->get('privacy')->getShow() == 1): ?>
                               checked="checked"
                           <?php endif; ?>
                       <?php else: ?>
                           checked="checked"
                       <?php endif; ?> />  
                <label for="show-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" name="show" value="0" id="show-off" <?php if ($this->get('privacy') != ''): ?>
                    <?php if ($this->get('privacy')->getShow() == 0): ?>
                               checked="checked"
                           <?php endif; ?>
                       <?php endif; ?> />  
                <label for="show-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
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
                   id="title" 
                   value="<?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="urltitle" class="col-lg-2 control-label">
            <?=$this->getTrans('urlTitle') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control" 
                   type="text" 
                   name="urltitle" 
                   id="urltitle" 
                   value="<?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getUrlTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="url" class="col-lg-2 control-label">
            <?=$this->getTrans('url') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control" 
                   type="text" 
                   name="url" 
                   id="url" 
                   placeholder="http://" 
                   value="<?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getUrl()); } ?>" />
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
                   rows="5"><?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('privacy') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>
