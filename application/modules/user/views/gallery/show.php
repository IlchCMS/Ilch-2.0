<?php $commentMapper = new \Modules\Comment\Mappers\Comment(); ?>

<style>
@media (max-width: 990px) {
    #gallery > [class*="col-"] {
        padding: 0 !important;
    }
}

.panel-heading ~ .panel-image img.panel-image-preview {
    border-radius: 0;
}

.panel-body {
    overflow: hidden;
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

<h1><?=$this->getTrans('menuGallery') ?></h1>
<?php if ($this->get('image') != ''): ?>
    <div id="gallery">
        <?php foreach ($this->get('image') as $image): ?>
            <?php $commentsCount = $commentMapper->getCountComments('user/gallery/showimage/user/'.$this->getRequest()->getParam('user').'/id/'.$image->getId()); ?>
            <div class="col-xs-6 col-md-4 col-lg-3 col-sm-4">
                <div class="card panel-default">
                    <div class="vard-image img-thumbnail">
                        <a href="<?=$this->getUrl(['action' => 'showimage', 'user' => $this->getRequest()->getParam('user'), 'id' => $image->getId()]) ?>">
                        <?php if (file_exists($image->getImageThumb())): ?>
                            <img src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>" class="panel-image-preview" alt="<?=$image->getImageTitle() ?>" />
                        <?php else: ?>
                            <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>" class="panel-image-preview" alt="<?=$image->getImageTitle() ?>" />
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <i class="fa-regular fa-comment"></i> <?=$commentsCount ?>
                        <i class="fa-solid fa-eye"> <?=$image->getVisits() ?></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noImages') ?>
<?php endif; ?>

<?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'user' => $this->getRequest()->getParam('user'), 'id' => $this->getRequest()->getParam('id')]) ?>
