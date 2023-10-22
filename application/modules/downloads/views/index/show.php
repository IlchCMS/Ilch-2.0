<?php $commentMapper = new \Modules\Comment\Mappers\Comment();?>

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
#gallery img{
    min-height: 20px;
}
</style>

<div id="gallery">
    <?php
    foreach ($this->get('file') as $file):
    $commentsCount = $commentMapper->getCountComments('downloads/index/showfile/id/'.$file->getId());
    $image = '';
    if ($file->getFileImage() != '') {
        $image = $this->getBaseUrl($file->getFileImage());
    }else {
        $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png');
    }
    ?>
        <div class="col-6 col-lg-4 col-xl-3 col-md-4">
            <div class="card card-default">
                <div class="card-image me-auto ms-auto thumbnail">
                    <a href="<?=$this->getUrl(['action' => 'showfile', 'id' => $file->getId()]) ?>">
                        <img src="<?=$image ?>" class="panel-image-preview" alt="<?=$this->escape($file->getFileTitle()) ?>" />
                    </a>
                </div>
                <div class="card-footer text-center">
                    <i class="fa-solid fa-pencil"></i> <?=$this->escape($file->getFileTitle()) ?><br>
                    <i class="fa-regular fa-comment"></i> <?=$commentsCount ?>
                    <i class="fa-solid fa-eye"></i> <?=$file->getVisits() ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($this->get('file'))) : ?>
        <?=$this->getTrans('downloadNotFound') ?>
    <?php endif; ?>
</div>
<?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>
