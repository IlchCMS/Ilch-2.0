<?php $image = $this->get('image'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <legend><?=$this->getTrans('treatImage') ?></legend>
            <form class="form-horizontal" method="POST" action="">
                <?=$this->getTokenField() ?>
                <div id="gallery">
                    <div class="col-md-5">
                        <a href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                            <img class="thumbnail" src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>"/>
                        </a>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="imageTitleInput" class="col-lg-4 control-label">
                                <?=$this->getTrans('title') ?>:
                            </label>
                            <div class="col-lg-8">
                                <input type="text"
                                       class="form-control"
                                       id="imageTitleInput"
                                       name="imageTitle"
                                       value="<?=$image->getImageTitle() ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="imageDescInput" class="col-lg-4 control-label">
                                <?=$this->getTrans('description') ?>:
                            </label>
                            <div class="col-lg-8">
                                <textarea class="form-control"
                                          id="imageDescInput"
                                          name="imageDesc"
                                          rows="8"><?=$image->getImageDesc() ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?=$this->getSaveBar('saveButton') ?>
            </form>
        </div>
    </div>
</div>
