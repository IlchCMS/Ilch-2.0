<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend>
    <?php
    if ($this->get('link') != '') {
        echo $this->getTrans('menuActionEditLink');
    } else {
        echo $this->getTrans('menuActionNewLink');
    }
    ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
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
            <?=$this->getTrans('link') ?>:
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
            <?=$this->getTrans('banner') ?>:
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
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="desc" 
                      cols="45" 
                      rows="3"><?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getDesc()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('category'); ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="catId">
                <option>-- <?=$this->getTrans('optionNoCategory')?> --</option>
                <?php
                if ($this->get('cats') != '') {
                    foreach ($this->get('cats') as $model) {
                        $selected = '';

                        if ($this->get('link') != '' && $this->get('link')->getCatId() == $model->getId()) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option '.$selected.' value="'.$model->getId().'">'.$this->escape($model->getName()).'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    if ($this->get('link') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
