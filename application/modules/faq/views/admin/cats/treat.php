<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <legend>
        <?php if ($this->get('cat') != ''): ?>
            <?=$this->getTrans('edit') ?>
       <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('cat') != '') { echo $this->escape($this->get('cat')->getTitle()); } ?>" />
        </div>
    </div>
    <?php if ($this->get('cat') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>
