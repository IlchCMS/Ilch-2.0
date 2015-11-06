<?php 
$image = $this->get('image');
?>

<legend><?=$this->getTrans('treatImage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div id="gallery">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                    <img class="thumbnail" src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>"/>
                </a>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="imageTitleInput" class="col-lg-2 control-label">
                        <?=$this->getTrans('imageTitle') ?>:
                    </label>
                    <div class="col-lg-8">
                        <input class="form-control"
                               type="text"
                               name="imageTitle"
                               id="imageTitleInput"
                               value="<?=$image->getImageTitle() ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="imageDescInput" class="col-lg-2 control-label">
                        <?=$this->getTrans('imageDesc') ?>:
                    </label>
                    <div class="col-lg-8">
                        <textarea class="form-control"
                                  id="imageDescInput"
                                  rows="8"
                                  name="imageDesc"><?=$image->getImageDesc() ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
