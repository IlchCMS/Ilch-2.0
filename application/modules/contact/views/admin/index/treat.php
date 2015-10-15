<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('receiver') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   value="<?php if ($this->get('receiver') != '') { echo $this->escape($this->get('receiver')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-2 control-label"><?=$this->getTrans('email') ?>:</label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="email"
                   id="email"
                   value="<?php if ($this->get('receiver') != '') { echo $this->escape($this->get('receiver')->getEmail()); } ?>" />
        </div>
    </div>
    <?php if ($this->get('receiver') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>
