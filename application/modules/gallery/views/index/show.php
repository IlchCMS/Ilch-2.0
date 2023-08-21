
<?php $commentMapper = new \Modules\Comment\Mappers\Comment(); ?>
<?php $venoboxDataArray = $this->get('venoboxDataArray'); ?>

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

<?php $commentMapper = new \Modules\Comment\Mappers\Comment(); ?>

<link href="<?=$this->getModuleUrl('static/venobox/venobox.min.css') ?>" media="screen" rel="stylesheet">

<div id="gallery">
    <?php foreach ($this->get('image') as $image): ?>
        <?php $commentsCount = $commentMapper->getCountComments ('gallery/index/showimage/id/'.$image->getId()); ?>
 
        <div class="col-xs-6 col-md-4 col-lg-3 col-sm-4">
            <div class="panel panel-default">
            <?php if (file_exists($image->getImageThumb())): ?>
                <a class="venobox" data-gall="gallery01" href="<?= $this->getUrl() . '/' . $image->getImageUrl() ?>" title="<?= $image->getImageTitle() ?> ">
                    <div class="panel-image thumbnail">
                        <img src="<?= $this->getUrl() . '/' . $image->getImageThumb() ?>" class="panel-image-preview" alt="<?= $this->escape($image->getImageTitle()) ?>" />
                    </div>
                </a>
            <?php else: ?>
                <a class="venobox" data-gall="gallery01" href="<?= $this->getUrl() . '/' . $image->getImageUrl() ?>" data-title="<?= $image->getImageTitle() ?> ">
                    <div class="panel-image thumbnail">
                        <img src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>" class="panel-image-preview" alt="<?=$this->getTrans('noMediaAlt') ?>" />
                    </div>
                </a>
            <?php endif; ?>

                <a href="<?=$this->getUrl(['action' => 'showimage', 'id' => $image->getId()]) ?>" title="<?=$this->getTrans('description')?>">
                    <div class="panel-footer text-center">
                        <i class="fa-regular fa-comment"></i> <?=$commentsCount ?>
                        <i class="fa-solid fa-eye"></i> <?=$image->getVisits() ?>
                    </div>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>

<script src="<?=$this->getModuleUrl('static/venobox/venobox.min.js') ?>"></script>

<script>
    new VenoBox({
        selector: ".venobox",
        titleStyle: 'bar',
    <?php foreach ($this->get('venoboxOptions') as $param => $value): ?>
    <?php if ($value === "0" || $value === "1"): ?>
    <?php echo $param; ?>: <?php echo $value === "1" ? 'true' : 'false'; ?>,
    <?php else: ?>
    <?php echo $param; ?>: "<?php echo $value; ?>",
    <?php endif; ?>
    <?php endforeach; ?>

    });
</script>






