<legend>
    <?php if ($this->get('link') != '') {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</legend>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($this->get('cats') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group<?=in_array('catId', $this->get('errorFields')) ? ' has-error' : '' ?>">
            <label for="catId" class="col-lg-2 control-label">
                <?=$this->getTrans('cat'); ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="catId" name="catId">
                    <?php foreach ($this->get('cats') as $model) {
                        $selected = '';

                        if ($this->get('faq') != '' && $this->get('faq')->getCatId() == $model->getId()) {
                            $selected = 'selected="selected"';
                        } elseif ($this->getRequest()->getParam('catId') != '' && $this->getRequest()->getParam('catId') == $model->getId()) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option '.$selected.' value="'.$model->getId().'">'.$this->escape($model->getTitle()).'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group<?=in_array('question', $this->get('errorFields')) ? ' has-error' : '' ?>">
            <label for="question" class="col-lg-2 control-label">
                <?=$this->getTrans('question') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="question"
                       name="question"
                       value="<?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->getQuestion()); } else { echo $this->get('post')['question']; } ?>" />
            </div>
        </div>
        <div class="form-group<?=in_array('answer', $this->get('errorFields')) ? ' has-error' : '' ?>">
            <label for="ck_1" class="col-lg-2 control-label">
                <?=$this->getTrans('answer') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="answer"
                          cols="45"
                          rows="3"
                          toolbar="ilch_html"><?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->getAnswer()); } else { echo $this->get('post')['answer']; } ?></textarea>
            </div>
        </div>
        <?php if ($this->get('faq') != '') {
            echo $this->getSaveBar('updateButton');
        } else {
            echo $this->getSaveBar('addButton');
        }
        ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
