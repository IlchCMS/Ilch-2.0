<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend>
        <?php
        if ($this->get('receiver') != '') {
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
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   value="<?php if ($this->get('receiver') != '') { echo $this->escape($this->get('receiver')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-2 control-label">
                <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="email"
                   id="email"
                   value="<?php if ($this->get('receiver') != '') { echo $this->escape($this->get('receiver')->getEmail()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('receiver') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
