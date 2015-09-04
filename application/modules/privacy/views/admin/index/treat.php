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
            <div class="radio">
                <label>
                    <input type="radio" 
                       name="show" 
                       id="show" 
                       value="1" 
                       <?php if ($this->get('privacy') != ''): ?>
                           <?php if ($this->get('privacy')->getShow() == 1): ?>
                               checked="checked"
                           <?php endif; ?>
                        <?php else: ?>
                            checked="checked"
                       <?php endif; ?>
                       > <?=$this->getTrans('yes') ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" 
                       name="show" 
                       id="show" 
                       value="0" 
                       <?php if ($this->get('privacy') != ''): ?>
                            <?php if ($this->get('privacy')->getShow() == 0): ?>
                                checked="checked"
                            <?php endif; ?>
                       <?php endif; ?>
                       > <?=$this->getTrans('no') ?>
                </label>
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
            <textarea class="form-control" 
                   name="text" 
                   id="ilch_html" 
                   rows="5"><?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('privacy') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>
