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
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->trans('name'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="Name"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-lg-2 control-label">
            <?php echo $this->trans('link'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="link"
                   id="link"
                   placeholder="http://"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getLink()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="banner" class="col-lg-2 control-label">
            <?php echo $this->trans('banner'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="banner"
                   id="banner"
                   placeholder="http://"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getBanner()); } ?>" />
        </div>
    </div>
    <div class="form-group">
<<<<<<< HEAD:application/modules/link/views/admin/index/treat.php
        <label for="cat_id" class="col-lg-2 control-label">
            <?php echo $this->trans('category'); ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="cat_id">
                <?php
                    foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                        $sel = '';

                        if ($this->get('cat_id') == $key) {
                            $sel = 'selected="selected"';
                        }
                        echo '<option '.$sel.' value="'.$key.'">'.$this->escape($value).'</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
=======
>>>>>>> e4d3202f6ff91046d083383cf0ef3af3bb1f594c:application/modules/link/views/admin/index/treatLink.php
        <label for="desc" class="col-lg-2 control-label">
            <?php echo $this->trans('description'); ?>:
        </label>
        <div class="col-lg-4">
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
