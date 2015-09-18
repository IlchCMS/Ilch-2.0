<?php $jobs = $this->get('jobs'); ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('jobs') != ''): ?>
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
                       <?php if ($this->get('jobs') != ''): ?>
                           <?php if ($this->get('jobs')->getShow() == 1): ?>
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
                       <?php if ($this->get('jobs') != ''): ?>
                            <?php if ($this->get('jobs')->getShow() == 0): ?>
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
