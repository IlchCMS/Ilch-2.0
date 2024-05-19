<?php

/** @var \Ilch\View $this */

$commentMapper = new \Modules\Comment\Mappers\Comment();
/** @var \Modules\Gallery\Models\Image $image */
$image = $this->get('image');
?>

<style>
    @media (max-width: 990px) {
        #gallery > [class*="col-"] {
            padding: 0 !important;
        }
    }

    .panel-heading ~ .panel-image img.panel-image-preview {
        border-radius: 0;
    }

    .panel-image ~ .panel-footer a {
        padding: 0 10px;
        font-size: 1.3em;
        color: rgb(100, 100, 100);
    }

    .panel-footer{
        padding: 5px !important;
        color: #BBB;
    }

    .panel-footer:hover{
        color: #000;
    }

    .thumbnail {
        position:relative;
        overflow:hidden;
        margin-bottom: 0 !important;
    }
</style>

<?php if (!empty($image)) : ?>
    <?php $commentsCount = $commentMapper->getCountComments('gallery/index/showimage/id/' . $image->getId()); ?>
    <div class="card card-default">
        <div class="card-image img-thumbnail">
        <?php if (file_exists($image->getImageThumb())) : ?>
            <a href="<?=$this->getUrl(['module' => 'gallery', 'controller' => 'index', 'action' => 'showimage', 'id' => $image->getId()]) ?>">
                <?php $altText = (empty($image->getImageTitle())) ? basename($image->getImageUrl()) : $image->getImageTitle(); ?>
                <img src="<?=$this->getUrl() . '/' . $image->getImageThumb() ?>" class="panel-image-preview" alt="<?=$this->escape($altText) ?>" />
            </a>
        <?php else : ?>
            <?=$this->getTrans('pictureMissing') ?>
        <?php endif; ?>
        </div>
        <div class="card-footer text-center">
            <i class="fa-regular fa-comment"></i> <?=$commentsCount ?>
            <i class="fa-solid fa-eye"> <?=$image->getVisits() ?></i>
        </div>
    </div>
<?php else : ?>
    <?=$this->getTrans('noPictures') ?>
<?php endif; ?>
