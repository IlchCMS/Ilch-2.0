<?php

/** @var \Ilch\View $this */

/** @var \Modules\Gallery\Models\Image $image */
$image = $this->get('image');
?>

<h1><?=$this->getTrans('treatImage') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div id="gallery">
        <div class="row">
            <div class="col-lg-4">
                <a href="<?=$this->getUrl() . '/' . $image->getImageUrl() ?>">
                    <img class="img-thumbnail" src="<?=$this->getUrl() . '/' . $image->getImageUrl() ?>" alt="<?=$this->escape($image->getImageTitle()) ?>"/>
                </a>
            </div>
            <div class="col-lg-8">
                <div class="row mb-3">
                    <label for="imageTitleInput" class="col-xl-2 control-label">
                        <?=$this->getTrans('imageTitle') ?>:
                    </label>
                    <div class="col-xl-8">
                        <input type="text"
                               class="form-control"
                               id="imageTitleInput"
                               name="imageTitle"
                               value="<?=$this->escape($image->getImageTitle()) ?>" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="imageDescInput" class="col-xl-2 control-label">
                        <?=$this->getTrans('imageDesc') ?>:
                    </label>
                    <div class="col-xl-8">
                        <textarea class="form-control"
                                  id="imageDescInput"
                                  rows="8"
                                  name="imageDesc"><?=$this->escape($image->getImageDesc()) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
