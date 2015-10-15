<legend>
    <?php if ($this->get('link') != ''): ?>
        <?=$this->getTrans('edit') ?>
    <?php else: ?>
        <?=$this->getTrans('add') ?>
    <?php endif; ?>
</legend>
<?php if ($this->get('cats') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="cats" class="col-lg-2 control-label">
                <?=$this->getTrans('cat'); ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" name="catId">
                    <?php
                        foreach ($this->get('cats') as $model) {
                            $selected = '';

                            if ($this->get('faq') != '' && $this->get('faq')->getCatId() == $model->getId()) {
                                $selected = 'selected="selected"';
                            }elseif ($this->getRequest()->getParam('catId') != '' && $this->getRequest()->getParam('catId') == $model->getId()) {
                                $selected = 'selected="selected"';
                            }

                            echo '<option '.$selected.' value="'.$model->getId().'">'.$this->escape($model->getTitle()).'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="question" class="col-lg-2 control-label">
                <?=$this->getTrans('question') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="question"
                       id="question"
                       value="<?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->getQuestion()); } ?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="answer" class="col-lg-2 control-label">
                <?=$this->getTrans('answer') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          name="answer"
                          cols="45"
                          id="ck_1"
                          toolbar="ilch_html"
                          rows="3"><?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->getAnswer()); } ?></textarea>
            </div>
        </div>
        <?php if ($this->get('faq') != ''): ?>
            <?=$this->getSaveBar('updateButton') ?>
        <?php else: ?>
            <?=$this->getSaveBar('addButton') ?>
        <?php endif; ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>
