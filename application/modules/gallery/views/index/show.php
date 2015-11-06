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

<div id="gallery">
    <?php foreach ($this->get('image') as $image): ?>
        <?php $commentsCount = $commentMapper->getCountComments('gallery/index/showimage/gallery/'.$this->getRequest()->getParam('id').'/id/'.$image->getId()); ?>
        <div class="col-xs-6 col-md-4 col-lg-3 col-sm-4">
            <div class="panel panel-default">
                <div class="panel-image thumbnail">
                    <a href="<?=$this->getUrl(array('action' => 'showimage', 'gallery'  => $this->getRequest()->getParam('id'), 'id' => $image->getId())) ?>">
                        <img src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>" class="panel-image-preview" alt="<?=$image->getImageTitle() ?>" />
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

<?=$this->get('pagination')->getHtml($this, array('action' => 'show', 'id' => $this->getRequest()->getParam('id'))) ?>
