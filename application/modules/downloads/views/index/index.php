<?php

/** @var \Ilch\View $this */
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
.lib-panel .lib-row.lib-desc a {
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

.text-start {
    font-size: 15px;
}
</style>

<h1><?=$this->getTrans('downloads') ?></h1>
<?php
/** @var \Modules\Downloads\Models\DownloadsItem[]|null $downloadsItems */
$downloadsItems = $this->get('downloadsItems');

/**
 * @param \Modules\Downloads\Models\DownloadsItem $item
 * @param \Ilch\View $obj
 */
function rec(\Modules\Downloads\Models\DownloadsItem $item, \Ilch\View $obj)
{
    /** @var \Modules\Downloads\Mappers\Downloads $downloadsMapper */
    $downloadsMapper = $obj->get('downloadsMapper');
    /** @var \Modules\Downloads\Mappers\File $fileMapper */
    $fileMapper = $obj->get('fileMapper');

    $subItems = $downloadsMapper->getDownloadsItemsByParent($item->getId());
    $fileCount = $fileMapper->getCountOfFilesByItemId($item->getId());

    if ($item->getType() === 0) {
        echo '<div class="page-header">
              <h4>' . $obj->getTrans('cat') . ': ' . $obj->escape($item->getTitle()) . '  <small>' . $obj->escape($item->getDesc()) . '</small>
              </h4><hr>';
    }
    if ($item->getType() != 0) {
        $lastFile = $fileMapper->getLastFileByItemId($item->getId()) ;
        $image = $obj->getBaseUrl('application/modules/media/static/img/nomedia.png');
        if ($lastFile && $lastFile->getFileImage() != '') {
            $image = $obj->getBaseUrl($lastFile->getFileImage()) ;
        }
        echo '<div class="col-lg-12 no-padding lib-item" data-category="view">
                <div class="lib-panel">
                    <div class="row box-shadow">
                        <div class="col-lg-4">
                            <a href="' . $obj->getUrl(['controller' => 'index', 'action' => 'show','id' => $item->getId()]) . '" >
                                <img class="lib-img-show" src="' . $image . '">
                            </a>
                        </div>
                        <div class="col-lg-8">
                            <div class="lib-row lib-header">
                                <a href="' . $obj->getUrl(['controller' => 'index', 'action' => 'show','id' => $item->getId()]) . '" >
                                    ' . $obj->escape($item->getTitle()) . '
                                </a>
                                <p class="text-start">' . $obj->getTrans('files') . ': ' . $fileCount . '</p>
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
            rec($subItem, $obj);
        }
    }
}
?>
<div class="row">
    <div class="col-xl-12">
        <ul class="media-list">
            <?php
            if (!empty($downloadsItems)) {
                foreach ($downloadsItems as $item) {
                    rec($item, $this);
                }
            } else {
                echo $this->getTrans('noDownloads');
            }
            ?>
        </ul>
    </div>
</div>
