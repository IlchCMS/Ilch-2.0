<?php $post = $this->get('post'); ?>

<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <div class="row">
        <div class="col-lg-12">
            <div class="new-post-head ilch-head">
                <?=$this->getTrans('editPost') ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="new-topic ilch-bg ilch-border">
                <form class="form-horizontal" method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
                        <div class="col-lg-12">
                            <textarea class="form-control ckeditor"
                                      id="ck_1"
                                      name="text"
                                      toolbar="ilch_bbcode"><?=$post->getText() ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <input type="submit"
                                   class="btn btn-primary"
                                   name="editPost"
                                   value="<?=$this->getTrans('edit'); ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?=$this->getDialog('smiliesModal', $this->getTrans('smilies'), '<iframe frameborder="0"></iframe>'); ?>
