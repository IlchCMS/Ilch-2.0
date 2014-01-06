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
        <label for="name" class="col-xs-2 control-label">
            <?php echo $this->trans('name'); ?>:
        </label>
        <div class="col-xs-2">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   value="<?php if ($this->get('receiver') != '') { echo $this->escape($this->get('receiver')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-xs-2 control-label">
            <?php echo $this->trans('email'); ?>:
        </label>
        <div class="col-xs-2">
            <input class="form-control"
                   type="text"
                   name="email"
                   id="email"
                   value="<?php if ($this->get('receiver') != '') { echo $this->escape($this->get('receiver')->getEmail()); } ?>" />
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php
            if ($this->get('receiver') != '') {
                echo $this->trans('editButton');
            } else {
                echo $this->trans('addButton');
            }
            ?>
        </button>
    </div>
</form>
