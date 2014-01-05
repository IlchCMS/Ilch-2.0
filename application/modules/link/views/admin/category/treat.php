<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('category') != '') {
            echo $this->trans('menuActionEditCategory');
        } else {
            echo $this->trans('menuActionNewCategory');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->trans('name'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="Name"
                   value="<?php if ($this->get('category') != '') { echo $this->escape($this->get('category')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?php echo $this->trans('desc'); ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="desc" 
                      cols="45" 
                      rows="3"><?php if ($this->get('category') != '') { echo $this->escape($this->get('category')->getDesc()); } ?></textarea>
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php
            if ($this->get('category') != '') {
                echo $this->trans('editButton');
            } else {
                echo $this->trans('addButton');
            }
            ?>
        </button>
    </div>
</form>
