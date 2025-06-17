<?php
$galleryMapper = $this->get('galleryMapper');
$galleryItems = $this->get('galleryItems');
$imageMapper = $this->get('imageMapper');

function rec($item, $galleryMapper, $obj, $imageMapper)
{
    $subItems = $galleryMapper->getGalleryItemsByParent($item->getUserId(), $item->getId());

    if ($item->getType() === 0) {
        echo '<div class="page-header">
              <h4>' . $obj->getTrans('cat') . ': ' . $obj->escape($item->getTitle()) . ' <small>' . $obj->escape($item->getDesc()) . '</small>
              </h4><hr>';
    }

    if ($item->getType() != 0) {
        $lastImage = $imageMapper->getLastImageByGalleryId($item->getId());
        if ($lastImage !== null && file_exists($lastImage->getImageThumb())) {
            $image = $obj->getBaseUrl($lastImage->getImageThumb());
        } else {
            $image = $obj->getBaseUrl('application/modules/media/static/img/nomedia.png');
        }

        echo '<div class="col-lg-12 no-padding lib-item" data-category="view">
                <div class="lib-panel">
                    <div class="row box-shadow">
                        <div class="col-lg-4">
                            <a href="' . $obj->getUrl(['action' => 'show', 'user' => $item->getUserId(), 'id' => $item->getId()]) . '">
                                <img class="lib-img-show" src="' . $image . '">
                            </a>
                        </div>
                        <div class="col-lg-8">
                            <div class="lib-row lib-header">
                                <a href="' . $obj->getUrl(['action' => 'show', 'user' => $item->getUserId(), 'id' => $item->getId()]) . '" >
                                    ' . $obj->escape($item->getTitle()) . '
                                </a>
                                <p class="text-start">' . $obj->getTrans('images') . ': ' . $imageMapper->getCountImageById($item->getId()) . '</p>
                                <div class="lib-header-seperator"></div>

                            </div>
                            <div class="lib-row lib-desc">
                                ' . $obj->escape($item->getDesc()) . '
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

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuGallery') ?></h1>
<div class="col-xl-12">
    <ul class="media-list">
        <?php if (!empty($galleryItems)) : ?>
            <?php foreach ($galleryItems as $item) : ?>
                <?php rec($item, $galleryMapper, $this, $imageMapper); ?>
            <?php endforeach; ?>
        <?php else : ?>
            <?=$this->getTrans('noImages') ?>
        <?php endif; ?>
    </ul>
</div>
