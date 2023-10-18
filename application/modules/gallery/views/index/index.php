<?php

/** @var \Ilch\View $this */

/** @var \Modules\Gallery\Mappers\Gallery $galleryMapper */
$galleryMapper = $this->get('galleryMapper');
/** @var \Modules\Gallery\Models\GalleryItem[]|null $galleryItems */
$galleryItems = $this->get('galleryItems');
/** @var \Modules\Gallery\Mappers\Image $imageMapper */
$imageMapper = $this->get('imageMapper');
$catTitle = '';
$catID = $catTitle;

/**
 * @param \Modules\Gallery\Models\GalleryItem $item
 * @param \Modules\Gallery\Mappers\Gallery $galleryMapper
 * @param \Ilch\View $obj
 * @return void
 */
function recCategory(\Modules\Gallery\Models\GalleryItem $item, \Modules\Gallery\Mappers\Gallery $galleryMapper, \Ilch\View $obj)
{
    $subItems = $galleryMapper->getGalleryItemsByParent($item->getId());

    if ($item->getType() === 0) {
        echo '<li>
                <a class="dropdown-item" href="#filter" data-filter=".X' . $obj->escape($item->getId()) . 'X"><i class="fa-solid fa-image"></i> ' . $obj->escape($item->getTitle()) . '</a>
              </li>';
    }
    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            recCategory($subItem, $galleryMapper, $obj);
        }
    }
}

/**
 * @param \Modules\Gallery\Models\GalleryItem $item
 * @param \Modules\Gallery\Mappers\Gallery $galleryMapper
 * @param \Ilch\View $obj
 * @param \Modules\Gallery\Mappers\Image $imageMapper
 * @param string $catID
 * @param string $catTitle
 * @return void
 */
function recGallery(\Modules\Gallery\Models\GalleryItem $item, \Modules\Gallery\Mappers\Gallery $galleryMapper, \Ilch\View $obj, \Modules\Gallery\Mappers\Image $imageMapper, string $catID, string $catTitle)
{
    $subItems = $galleryMapper->getGalleryItemsByParent($item->getId());
    if ($item->getType() === 0) {
        $catID = $obj->escape($item->getId());
        $catTitle = $obj->escape($item->getTitle());
    }
    if ($item->getType() != 0) {
        $lastImage = $imageMapper->getLastImageByGalleryId($item->getId());
        $imageTitle = (!empty($lastImage)) ? $obj->escape($lastImage->getImageTitle()) : $obj->getTrans('noMediaAlt');
        if ($lastImage !== null && $lastImage->getImageThumb() != '') {
            $image = $obj->getBaseUrl($lastImage->getImageThumb());
        } else {
            $image = $obj->getBaseUrl('application/modules/media/static/img/nomedia.png');
        }
        echo '<div class="col-md-11 padding lib-item X' . $catID . 'X" data-category="view">
                <div class="lib-panel">
                    <div class="row box-shadow">
                        <div class="col-md-3">
                            <a href="' . $obj->getUrl(['controller' => 'index', 'action' => 'show','id' => $item->getId()]) . '" >
                                <img class="lib-img-show" src="' . $image . '" alt="' . $imageTitle . '" >
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="lib-row lib-header">
                                <a href="' . $obj->getUrl(['controller' => 'index', 'action' => 'show','id' => $item->getId()]) . '" >
                                    ' . $obj->escape($item->getTitle()) . '
                                </a>
                                <p class="text-left">' . $obj->getTrans('cat') . ': ' . $catTitle . '
                                <br />' . $obj->getTrans('images') . ': ' . $imageMapper->getCountImageById($item->getId()) . '</p>
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
            recGallery($subItem, $galleryMapper, $obj, $imageMapper, $catID, $catTitle);
        }
    }
}
?>
<link href="<?=$this->getModuleUrl('static/css/gallery.css') ?>" rel="stylesheet">
<h1><?=$this->getTrans('menuGallery') ?> <span class="catinfo"></span></h1>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <a class="navbar-brand"><?=$this->getTrans('navigation') ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    <div id="navbar-collapse" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownConfig" data-bs-toggle="dropdown" data-bs-target="#navbar-collapse" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-arrow-down"></i> <?=$this->getTrans('allCat') ?></a>
          <ul id="filters" class="dropdown-menu" aria-labelledby="dropdownConfig">
            <li class="active"><a href="#filter" data-filter="*"><i class="fa-solid fa-image" class="dropdown-item"></i> <?=$this->getTrans('allCat') ?></a></li>
            <?php if (!empty($galleryItems)) : ?>
                <?php foreach ($galleryItems as $item) : ?>
                    <?php recCategory($item, $galleryMapper, $this); ?>
                <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </li>
        <li id="sorts" class="nav-item"><a href="#filter" data-sort-direction="asc" data-sort-by="name"><i class="sorticon fa-solid fa-arrow-down-9-1"></i> <?=$this->getTrans('sort') ?></a></li>
      </ul>
      <form class="d-flex">
        <input type="text" id="quicksearch" class="form-control" placeholder="<?=$this->getTrans('search') ?>">
      </form>
    </div>
  </div>
</nav>

<div id="gallery" class="col-lg-12">
    <ul class="media-list">
        <?php if (!empty($galleryItems)) : ?>
            <?php foreach ($galleryItems as $item) : ?>
                <?php recGallery($item, $galleryMapper, $this, $imageMapper, $catID, $catTitle); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<script src="<?=$this->getModuleUrl('static/js/isotope.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/custom.js') ?>"></script>
