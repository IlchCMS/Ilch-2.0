<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">

<h3 class="blue-header col-lg-12"><?=$this->getTrans('newPost') ?></h3>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea class="form-control ckeditor"
                              id="ck_1"
                              name="text"
                              toolbar="ilch_bbcode"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="submit"
                           class="btn"
                           name="saveNewPost"
                           value="<?php echo $this->getTrans('add'); ?>" />
                </div>
            </div>
        </div>
    </div>
</form>

<?=$this->getDialog('smiliesModal', $this->getTrans('smilies'), '<iframe frameborder="0"></iframe>'); ?>
