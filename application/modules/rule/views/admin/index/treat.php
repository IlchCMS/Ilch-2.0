<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('rule') != '') {
            echo $this->getTrans('edit');
        } else {
            echo $this->getTrans('add');
        }
        ?>
    </legend>
    <div class="form-group">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('paragraph') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="paragraph"
                   name="paragraph"
                   min="1"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getParagraph()); } else { echo '1'; } ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('rule') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
