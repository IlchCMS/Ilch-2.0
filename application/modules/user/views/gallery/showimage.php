<?php
$image = $this->get('image');
$nowDate = new \Ilch\Date();
$commentsClass = new Ilch\Comments();
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuGallery') ?></h1>
<div id="gallery">
    <div class="row">
        <div class="col-lg-6">
            <a href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                <img class="img-thumbnail" src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>" alt="<?=$image->getImageTitle() ?>"/>
            </a>
        </div>
        <div class="col-lg-6">
            <h3><?=$this->escape($image->getImageTitle()) ?></h3>
            <p><?=$this->escape($image->getImageDesc()) ?></p>
        </div>
    </div>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $image, $this) ?>
