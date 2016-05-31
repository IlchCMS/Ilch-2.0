<?php $post = $this->get('post'); ?>

<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">

<h3 class="blue-header col-lg-12"><?=$this->getTrans('editPost') ?></h3>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea id="ck_1"
                              class="form-control ckeditor"
                              toolbar="ilch_bbcode"
                              name="text">
                              <?=$post->getText() ?>
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="submit"
                           name="editPost"
                           class="btn"
                           value="<?=$this->getTrans('edit'); ?>" />
                </div>
            </div>
        </div>
    </div>
</form>

<?=$this->getDialog('smiliesModal', $this->getTrans('smilies'), '<iframe frameborder="0"></iframe>'); ?>
