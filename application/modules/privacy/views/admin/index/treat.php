<?php $privacy = $this->get('privacy'); ?>

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('privacy') != '') {
            echo $this->getTrans('edit');
        } else {
            echo $this->getTrans('add');
        }
        ?>
    </legend>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('show') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="show-on" name="show" value="1" 
                    <?php if ($this->get('privacy') != ''): ?>
                        <?php if ($this->get('privacy')->getShow() == 1): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="show-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="show-off" name="show" value="0" 
                    <?php if ($this->get('privacy') != '' AND $this->get('privacy')->getShow() == 0): ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="show-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
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
                   value="<?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="urltitle" class="col-lg-2 control-label">
            <?=$this->getTrans('urlTitle') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="urltitle"
                   name="urltitle"
                   value="<?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getUrlTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="url" class="col-lg-2 control-label">
            <?=$this->getTrans('url') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="url"
                   name="url"
                   placeholder="http://" 
                   value="<?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getUrl()); } ?>" />
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
                      rows="5"><?php if ($this->get('privacy') != '') { echo $this->escape($this->get('privacy')->getText()); } ?></textarea>
        </div>
    </div>
    <?php if ($this->get('privacy') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
