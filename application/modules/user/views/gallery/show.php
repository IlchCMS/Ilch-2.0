<?php $commentMapper = new \Modules\Comment\Mappers\Comment(); ?>

<style>
@media (max-width: 990px) {
    #gallery > [class*="col-"] {
        padding: 0px !important;
    }
}

.panel-heading ~ .panel-image img.panel-image-preview {
    border-radius: 0px;
}

.panel-body {
    overflow: hidden;
}

.panel-image ~ .panel-footer a {
    padding: 0px 10px;
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
    margin-bottom: 0px !important;
}
</style>

<h1><?=$this->getTrans('menuGallery') ?></h1>
<?php if ($this->get('image') != ''): ?>
    <div id="gallery">
        <?php foreach ($this->get('image') as $image): ?>
            <?php $commentsCount = $commentMapper->getCountComments('user/gallery/showimage/user/'.$this->getRequest()->getParam('user').'/id/'.$image->getId()); ?>
            <div class="col-xs-6 col-md-4 col-lg-3 col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-image thumbnail">
                        <a href="<?=$this->getUrl(['action' => 'showimage', 'user' => $this->getRequest()->getParam('user'), 'id' => $image->getId()]) ?>">
                        <?php if (file_exists($image->getImageThumb())): ?>
                            <img src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>" class="panel-image-preview" alt="<?=$image->getImageTitle() ?>" />
                        <?php else: ?>
                            <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>" class="panel-image-preview" alt="<?=$image->getImageTitle() ?>" />
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="panel-footer text-center">
                        <i class="fa fa-comment-o"></i> <?=$commentsCount ?>
                        <i class="fa fa-eye"> <?=$image->getVisits() ?></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noImages') ?>
<?php endif; ?>

<?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>
