<legend>
    <?php
    if ($this->get('category') != '') {
        echo $this->getTrans('menuActionEditCategory');
    } else {
        echo $this->getTrans('menuActionNewCategory');
    }
    ?>
</legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?php if ($this->get('category') != '') { echo $this->escape($this->get('category')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="desc"
                      cols="45"
                      rows="3"><?php if ($this->get('category') != '') { echo $this->escape($this->get('category')->getDesc()); } ?></textarea>
        </div>
    </div>
    <?php
    if ($this->get('category') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
