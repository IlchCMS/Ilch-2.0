<?php $jobs = $this->get('jobs'); ?>

<legend>
    <?php
    if ($this->get('jobs') != '') {
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

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('show') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="jobs-yes" name="show" value="1"
                    <?php if ($this->get('jobs') != ''): ?>
                        <?php if ($this->get('jobs')->getShow() == 1): ?>
                            checked="checked"
                        <?php endif; ?>
                    <?php else: ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="jobs-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="jobs-no" name="show" value="0"
                    <?php if ($this->get('jobs') != '' AND $this->get('jobs')->getShow() == 0): ?>
                        checked="checked"
                    <?php endif; ?> />
                <label for="jobs-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
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
                   value="<?php if ($this->get('jobs') != '') { echo $this->escape($this->get('jobs')->getTitle()); } else { echo $this->get('post')['title']; } ?>" />
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
                      rows="5"><?php if ($this->get('jobs') != '') { echo $this->escape($this->get('jobs')->getText()); } else { echo $this->get('post')['text']; } ?></textarea>
        </div>
    </div>
    <div class="form-group <?=in_array('email', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="email" class="col-lg-2 control-label">
            <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="email"
                   name="email"
                   value="<?php if ($this->get('jobs') != '') { echo $this->escape($this->get('jobs')->getEmail()); } else { echo $this->get('post')['email']; } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('jobs') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
