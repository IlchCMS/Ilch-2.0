<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('link') != '') {
            echo $this->trans('menuActionEditLink');
        } else {
            echo $this->trans('menuActionNewLink');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-xs-2 control-label">
            <?php echo $this->trans('name'); ?>:
        </label>
        <div class="col-xs-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="Name"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-xs-2 control-label">
            <?php echo $this->trans('link'); ?>:
        </label>
        <div class="col-xs-4">
            <input class="form-control"
                   type="text"
                   name="link"
                   id="link"
                   placeholder="http://"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getLink()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="banner" class="col-xs-2 control-label">
            <?php echo $this->trans('banner'); ?>:
        </label>
        <div class="col-xs-4">
            <input class="form-control"
                   type="text"
                   name="banner"
                   id="banner"
                   placeholder="http://"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getBanner()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-xs-2 control-label">
            <?php echo $this->trans('description'); ?>:
        </label>
        <div class="col-xs-4">
            <textarea class="form-control"
                      name="desc" 
                      cols="45" 
                      rows="3"><?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getDesc()); } ?></textarea>
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php
            if ($this->get('link') != '') {
                echo $this->trans('editButton');
            } else {
                echo $this->trans('addButton');
            }
            ?>
        </button>
    </div>
</form>
