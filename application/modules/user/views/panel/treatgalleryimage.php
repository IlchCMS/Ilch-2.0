<?php $image = $this->get('image'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('treatImage') ?></h1>
            <form class="form-horizontal" method="POST">
                <?=$this->getTokenField() ?>
                <div id="gallery">
                    <div class="col-md-5">
                        <a href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                            <img class="thumbnail" src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>"/>
                        </a>
                    </div>
<<<<<<< Updated upstream
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="imageTitleInput" class="col-lg-4 control-label">
=======
                    <div class="col-lg-7">
                        <div class="row lg-3">
                            <label for="imageTitleInput" class="col-xl-4 control-label">
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                        <div class="form-group">
                            <label for="imageDescInput" class="col-lg-4 control-label">
=======
                        <div class="row lg-3">
                            <label for="imageDescInput" class="col-xl-4 control-label">
>>>>>>> Stashed changes
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
