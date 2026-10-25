<?php $image = $this->get('image'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-xl-12 profile">
        <?php include APPLICATION_PATH . '/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('treatImage') ?></h1>
            <form method="POST">
                <?=$this->getTokenField() ?>
                <div id="gallery" class="g-3">
                    <div class="col-lg-5 mb-3">
                        <a href="<?=$this->getUrl() . '/' . $image->getImageUrl() ?>">
                            <img class="img-thumbnail" src="<?=$this->getUrl() . '/' . $image->getImageUrl() ?>"/>
                        </a>
                    </div>
                    <div class="col-lg-7">
                        <div class="row mb-3">
                            <label for="imageTitleInput" class="col-lg-4 col-form-label">
                                <?=$this->getTrans('title') ?>:
                            </label>
                            <div class="col-xl-8">
                                <input type="text"
                                       class="form-control"
                                       id="imageTitleInput"
                                       name="imageTitle"
                                       value="<?=$image->getImageTitle() ?>" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="imageDescInput" class="col-lg-4 col-form-label">
                                <?=$this->getTrans('description') ?>:
                            </label>
                            <div class="col-xl-8">
                                <textarea class="form-control"
                                          id="imageDescInput"
                                          name="imageDesc"
                                          rows="8"><?=$image->getImageDesc() ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="imageVisibilityInput" class="col-lg-4 col-form-label">
                                <?=$this->getTrans('visibility') ?>:
                            </label>
                            <div class="col-xl-8">
                                <select class="form-control" id="imageVisibilityInput" name="imageVisibility">
                                    <option value="1" <?= ((int)($image->getIsPublic() ?? 1)) === 1 ? 'selected' : '' ?>><?=$this->getTrans('public') ?></option>
                                    <option value="0" <?= ((int)($image->getIsPublic() ?? 1)) === 0 ? 'selected' : '' ?>><?=$this->getTrans('private') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <?=$this->getSaveBar('saveButton') ?>
            </form>
        </div>
    </div>
</div>
