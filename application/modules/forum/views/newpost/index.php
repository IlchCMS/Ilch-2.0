<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<h3 class="blue-header col-lg-12">newPost</h3>
<form class="form-horizontal" method="POST" action="">
    <div class="row">
        <div class="col-md-12">
            <?=$this->getTokenField() ?>
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea id="ck_1"
                              class="form-control ckeditor"
                              toolbar="ilch_bbcode"
                              name="text">
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="submit"
                           name="saveNewPost"
                           class="btn"
                           value="<?php echo $this->getTrans('add'); ?>" />
                </div>
            </div>
        </div>
    </div>
</form>