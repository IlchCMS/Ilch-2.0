<h1>
    <?php if (!empty($this->get('faq'))) {
        echo $this->getTrans('edit');
    } else {
        echo $this->getTrans('add');
    }
    ?>
</h1>
<?php if ($this->get('cats') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group <?=$this->validation()->hasError('catId') ? 'has-error' : '' ?>">
            <label for="catId" class="col-lg-2 control-label">
                <?=$this->getTrans('cat') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="catId" name="catId">
                    <?php foreach ($this->get('cats') as $model) {
                        $selected = '';

                        if ($this->get('faq') != '' && $this->get('faq')->catId == $model->id) {
                            $selected = 'selected="selected"';
                        } elseif ($this->getRequest()->getParam('catId') != '' && $this->getRequest()->getParam('catId') == $model->id) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option '.$selected.' value="'.$model->id.'">'.$this->escape($model->title).'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('question') ? 'has-error' : '' ?>">
            <label for="question" class="col-lg-2 control-label">
                <?=$this->getTrans('question') ?>:
            </label>
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="question"
                       name="question"
                       value="<?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->question); } else { echo $this->originalInput('question'); } ?>" />
            </div>
        </div>
        <div class="form-group <?=$this->validation()->hasError('answer') ? 'has-error' : '' ?>">
            <label for="ck_1" class="col-lg-2 control-label">
                <?=$this->getTrans('answer') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="answer"
                          cols="45"
                          rows="3"
                          toolbar="ilch_html"><?php if ($this->get('faq') != '') { echo $this->escape($this->get('faq')->answer); } else { echo $this->originalInput('answer'); } ?></textarea>
            </div>
        </div>
        <?php if (!empty($this->get('faq'))) {
            echo $this->getSaveBar('updateButton');
        } else {
            echo $this->getSaveBar('addButton');
        }
        ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noCategory') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
