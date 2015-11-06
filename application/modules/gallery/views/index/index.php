<?php
$galleryMapper = $this->get('galleryMapper');
$galleryItems = $this->get('galleryItems');
$imageMapper = $this->get('imageMapper');

function rec($item, $galleryMapper, $obj, $imageMapper)
{
    $subItems = $galleryMapper->getGalleryItemsByParent('1', $item->getId());
    $imageCount = $imageMapper->getCountImageById($item->getId());

    if ($item->getType() === 0){
        echo '<div class="page-header">
              <h4>'.$obj->getTrans('cat').': '.$item->getTitle().' <small>'.$item->getDesc().'</small>
              </h4><hr>';
    }

    if ($item->getType() != 0){
        $lastImage = $imageMapper->getLastImageByGalleryId($item->getId());

        if ($lastImage->getImageThumb() != '') {
            $image = $obj->getBaseUrl($lastImage->getImageThumb());
        } else {
            $image = $obj->getBaseUrl('application/modules/media/static/img/nomedia.png');
        }

        echo '<div class="col-md-12 no-padding lib-item" data-category="view">
                <div class="lib-panel">
                    <div class="row box-shadow">
                        <div class="col-md-4">
                            <a href="'.$obj->getUrl(array('controller' => 'index', 'action' => 'show','id' => $item->getId())).'" >
                                <img class="lib-img-show" src="'.$image.'">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="lib-row lib-header">
                                <a href="'.$obj->getUrl(array('controller' => 'index', 'action' => 'show','id' => $item->getId())).'" >
                                    '.$item->getTitle().'
                                </a>
                                <p class="text-left">'.$obj->getTrans('images').': '. count($imageCount).'</p>
                                <div class="lib-header-seperator"></div>
                                
                            </div>
                            <div class="lib-row lib-desc">
                                '.$item->getDesc().'
                            </div>
                        </div>
                    </div>
                </div>
            </div>';        
    }

    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            rec($subItem, $galleryMapper, $obj, $imageMapper);
        }
    }
}
?>

<style>
.lib-panel {
    margin-bottom: 20Px;
}
.lib-panel img {
    width: 100%;
    background-color: transparent;
}

.lib-panel .row,
.lib-panel .col-md-6 {
    padding: 0;
    background-color: #FFFFFF;
}

.lib-panel .lib-row {
    padding: 0 20px 0 20px;
}

.lib-panel .lib-row.lib-header {
    background-color: #FFFFFF;
    font-size: 20px;
    padding: 10px 20px 0 20px;
}

.lib-panel .lib-row.lib-header .lib-header-seperator {
    height: 2px;
    width: 100%;
    background-color: #d9d9d9;
    margin: 7px 0 7px 0;
}

.lib-panel .lib-row.lib-desc {
    position: relative;
    height: 100%;
    display: block;
    font-size: 13px;
}
.lib-panel .lib-row.lib-desc a{
    position: absolute;
    width: 100%;
    bottom: 10px;
    left: 20px;
}

.row-margin-bottom {
    margin-bottom: 20px;
}

.box-shadow {
    -webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,.10);
    box-shadow: 0 0 10px 0 rgba(0,0,0,.10);
}

.text-left{
    font-size: 15px;
}
</style>

<legend><?=$this->getTrans('menuGallery') ?></legend>
<div class="col-lg-12">
    <ul class="media-list">
        <?php if (!empty($galleryItems)): ?>
            <?php foreach ($galleryItems as $item): ?>
                <?php rec($item, $galleryMapper, $this, $imageMapper); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
