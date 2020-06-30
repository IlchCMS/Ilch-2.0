<?php
$image = $this->get('image');
$commentsClass = new Ilch\Comments();
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<div id="gallery">
    <div class="row">
        <div class="col-md-6">
            <a href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                <img class="thumbnail" src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>" alt="<?=$this->escape($image->getImageTitle()) ?>"/>
            </a>
        </div>
        <div class="col-md-6">
            <h3><?=$this->escape($image->getImageTitle()) ?></h3>
            <p><?=$this->escape($image->getImageDesc()) ?></p>
        </div>
    </div>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $image, $this) ?>
