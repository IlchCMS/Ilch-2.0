<?php


/** @var \Ilch\View $this */

/** @var \Modules\Gallery\Models\Image $image */
$image = $this->get('image');
$commentsClass = new Ilch\Comments();
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/venobox/venobox.min.css') ?>" media="screen" rel="stylesheet">

<div id="gallery">
    <div class="row">
        <div class="col-md-6">
        <?php if ($image->getImageUrl()) : ?>
            <a class="venobox" href="<?= $this->getUrl() . '/' . $image->getImageUrl() ?>">
                <img src="<?= $this->getUrl() . '/' . $image->getImageUrl() ?>" alt="<?= $this->escape($image->getImageTitle()) ?>" />
            </a>
        <?php else : ?>
            <a class="venobox" href="<?= $this->getUrl() . '/' . $image->getImageUrl() ?>">
                <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>" alt="<?=$this->getTrans('noMediaAlt') ?>" />
            </a>
        <?php endif; ?>
        </div>
    </div>
</div>

<div class="galleryImageDetails">
    <p><strong><?=$this->getTrans('imageTitle') ?>: </strong><?= $this->escape($image->getImageTitle()) ?></p>
    <p><strong><?=$this->getTrans('imageDesc') ?>: </strong><?= $this->escape($image->getImageDesc()) ?></p>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $image, $this) ?>

<script src="<?=$this->getModuleUrl('static/venobox/venobox.min.js') ?>"></script>
