<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id'))) ?>">
    <?=$this->getTokenField() ?>
    <legend>
    <?php
        if ($this->get('rule') != '') {
            echo $this->getTrans('menuActionEditRule');
        } else {
            echo $this->getTrans('menuActionNewRule');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('paragraph') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="number"
                   min="1"
                   name="paragraph"
                   id="paragraph"
                   placeholder="<?=$this->getTrans('paragraph') ?>"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getParagraph()); } ?>" />
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
                   placeholder="<?=$this->getTrans('title') ?>"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getTitle()); } ?>" />
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
                   rows="5"><?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getText()); } ?></textarea>
        </div>
    </div>
    <?php
    if ($this->get('rule') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>