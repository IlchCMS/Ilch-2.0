<link href="<?=$this->getModuleUrl('static/css/gallery.css') ?>" rel="stylesheet">

<?php
$galleryMapper = $this->get('galleryMapper');
$galleryItems = $this->get('galleryItems');
$imageMapper = $this->get('imageMapper');
$catID = $catTitle = '';

function recCategory($item, $galleryMapper, $obj, $imageMapper)
{   
    $subItems = $galleryMapper->getGalleryItemsByParent('1', $item->getId());

    if ($item->getType() === 0) {
        echo '<li>
                <a href="#filter" data-filter=".X'.$obj->escape($item->getId()).'X"><i class="fa fa-image"></i> '.$obj->escape($item->getTitle()).'</a>
              </li>';
    }
    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            recCategory($subItem, $galleryMapper, $obj, $imageMapper);
        }
    }
}

function recGallery($item, $galleryMapper, $obj, $imageMapper, $catID, $catTitle)
{
    $subItems = $galleryMapper->getGalleryItemsByParent('1', $item->getId());

    if ($item->getType() === 0) {
        $catID = $obj->escape($item->getId());
        $catTitle = $obj->escape($item->getTitle());
    }
    if ($item->getType() != 0) {
        $lastImage = $imageMapper->getLastImageByGalleryId($item->getId());

        if ($lastImage !== null && $lastImage->getImageThumb() != '') {
            $image = $obj->getBaseUrl($lastImage->getImageThumb());
        } else {
            $image = $obj->getBaseUrl('application/modules/media/static/img/nomedia.png');
        }
        echo '<div class="col-md-11 padding lib-item X'.$catID.'X" data-category="view">
                <div class="lib-panel">
                    <div class="row box-shadow">
                        <div class="col-md-3">
                            <a href="'.$obj->getUrl(['controller' => 'index', 'action' => 'show','id' => $item->getId()]).'" >
                                <img class="lib-img-show" src="'.$image.'">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="lib-row lib-header">
                                <a href="'.$obj->getUrl(['controller' => 'index', 'action' => 'show','id' => $item->getId()]).'" >
                                    '.$obj->escape($item->getTitle()).'
                                </a>
                                <p class="text-left">'.$obj->getTrans('cat').': '.$catTitle.'
                                <br />'.$obj->getTrans('images').': '.$imageMapper->getCountImageById($item->getId()).'</p>
                                <div class="lib-header-seperator"></div>
                            </div>
                            <div class="lib-row lib-desc">
                                '.$obj->escape($item->getDesc()).'
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

<h1><?=$this->getTrans('menuGallery') ?> <span class="catinfo"></span></h1>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <span class="sr-only"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"><?=$this->getTrans('navigation') ?></a>
    </div>
    <div id="navbar-collapse" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown active">
          <a class="dropdown-toggle" href="#" id="dropdownConfig" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-arrow-down"></i> <?=$this->getTrans('allCat') ?></a>
          <ul id="filters" class="dropdown-menu" aria-labelledby="dropdownConfig">
            <li class="active"><a href="#filter" data-filter="*"><i class="fa fa-image"></i> <?=$this->getTrans('allCat') ?></a></li>
            <?php if (!empty($galleryItems)): ?>
              <?php foreach ($galleryItems as $item): ?>
                <?php recCategory($item, $galleryMapper, $this, $imageMapper); ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </li>
        <li id="sorts"><a href="#filter" data-sort-direction="asc" data-sort-by="name"><i class="sorticon fa fa-sort-numeric-desc"></i> <?=$this->getTrans('sort') ?></a></li>
      </ul>
      <form class="nav navbar-form navbar-right">
        <input type="text" id="quicksearch" class="form-control" placeholder="<?=$this->getTrans('search') ?>">
      </form>
    </div>
  </div>
</nav>

<div id="gallery" class="col-lg-12">
    <ul class="media-list">
        <?php if (!empty($galleryItems)): ?>
            <?php foreach ($galleryItems as $item): ?>
                <?php recGallery($item, $galleryMapper, $this, $imageMapper, $catID, $catTitle); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<script src="<?=$this->getModuleUrl('static/js/isotope.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/custom.js') ?>"></script>