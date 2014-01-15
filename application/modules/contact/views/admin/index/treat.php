<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('receiver') != '') {
            echo $this->trans('menuActionEditReceiver');
        } else {
            echo $this->trans('menuActionNewReceiver');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->trans('name'); ?>:
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
            <?php echo $this->trans('email'); ?>:
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
        echo $this->getSaveBar('editButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
