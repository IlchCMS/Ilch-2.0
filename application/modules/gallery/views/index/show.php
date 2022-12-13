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

<?php $commentMapper = new \Modules\Comment\Mappers\Comment(); ?>

<link href="<?=$this->getVendorUrl('nicolafranchini/venobox/dist/venobox.min.css') ?>" media="screen" rel="stylesheet">

<div id="gallery">
    <?php foreach ($this->get('image') as $image): ?>
 
        <?php $commentsCount = $commentMapper->getCountComments ('gallery/index/showimage/id/'.$image->getId()); ?>
 
        <div class="col-xs-6 col-md-4 col-lg-3 col-sm-4">
            <div class="panel panel-default">
 
                <a class="venobox" data-gall="gallery01" href="<?=$this->getUrl().'/' .$image->getImageUrl() ?>"title="<?=$image->getImageTitle() ?> ">
 
                <div class="panel-image thumbnail">
                        <img src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>" class="panel-image-preview" alt="<?=$image->getImageTitle()?>" />
                </div>
 
                <a href="<?=$this->getUrl(['action' => 'showimage', 'id' => $image->getId()]) ?>" title="<?=$this->getTrans('description')?>">
 
 
                <div class="panel-footer text-center">
                    <i class="fa fa-comment-o"></i> <?=$commentsCount ?>
                    <i class="fa fa-eye"> <?=$image->getVisits() ?></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>

<script src="<?=$this->getVendorUrl('nicolafranchini/venobox/dist/venobox.min.js') ?>"></script>

<script>
    new VenoBox({
        selector: '.venobox',
        numeration: true,
        share: true,
        navTouch: true,
        spinner: 'pulse',
    })
    </script>
