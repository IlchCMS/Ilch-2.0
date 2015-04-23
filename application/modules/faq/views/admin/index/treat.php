<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('link') != ''): ?>
            <?=$this->getTrans('edit') ?>
        <?php else: ?>
            <?=$this->getTrans('add') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?php echo $this->getTrans('title'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="title"
                   id="title"
                   value="<?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="text" class="col-lg-2 control-label">
            <?php echo $this->getTrans('text'); ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control"
                      name="text"
                      cols="45"
                      id="ilch_html"
                      rows="3"><?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->getText()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('cat'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="catId">
                <option>-- <?=$this->getTrans('optionNoCategory')?> --</option>
                <?php
                foreach ($this->get('cats') as $model) {
                    $selected = '';

                    if ($this->get('faq') != '' && $this->get('faq')->getCatId() == $model->getId()) {
                        $selected = 'selected="selected"';
                    }

                    echo '<option '.$selected.' value="'.$model->getId().'">'.$this->escape($model->getTitle()).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <?php if ($this->get('faq') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>
