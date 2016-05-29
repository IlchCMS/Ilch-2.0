<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <legend>
        <?php
        if ($this->get('cat') != '') {
            echo $this->getTrans('edit');
       } else {
            echo $this->getTrans('add');
       }
       ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-3">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   value="<?php if ($this->get('cat') != '') { echo $this->escape($this->get('cat')->getName()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('cat') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
