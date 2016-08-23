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
    <div class="form-group <?=in_array('paragraph', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="paragraph" class="col-lg-2 control-label">
            <?=$this->getTrans('paragraph') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="paragraph"
                   name="paragraph"
                   min="1"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getParagraph()); } else { echo $this->get('post')['paragraph']; } ?>">
        </div>
    </div>
    <div class="form-group <?=in_array('title', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="title" class="col-lg-2 control-label">
            <?=$this->getTrans('title') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getTitle()); } else { echo $this->get('post')['title']; } ?>" />
        </div>
    </div>
    <div class="form-group <?=in_array('text', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="ck_1" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="text"
                      toolbar="ilch_html"
                      rows="5"><?php if ($this->get('rule') != '') { echo $this->escape($this->get('rule')->getText()); } else { echo $this->get('post')['text']; } ?></textarea>
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
