<?php

/** @var \Ilch\View $this */
?>
<link href="<?=$this->getModuleUrl('../downloads/static/css/frontend.css') ?>" rel="stylesheet">
<h1><?=$this->getTrans('downloads') ?></h1>
<?php
/** @var \Modules\Downloads\Models\DownloadsItem[]|null $downloadsItems */
$downloadsItems = $this->get('downloadsItems');

/** @var \Modules\Downloads\Models\DownloadsItem[]|null $subItems */
$subItems = $this->get('subItems');

/**
 * @param \Modules\Downloads\Models\DownloadsItem $item
 * @param \Ilch\View $obj
 */
function rec(\Modules\Downloads\Models\DownloadsItem $item, \Ilch\View $obj)
{
    /** @var \Modules\Downloads\Mappers\File $fileMapper */
    $fileMapper = $obj->get('fileMapper');
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
}
?>
<div class="row">
    <div class="col-xl-12">
        <ul class="media-list">
            <?php
            if (!empty($downloadsItems)) {
                foreach ($downloadsItems as $item) {
                    rec($item, $this);
                    foreach($subItems[$item->getId()] as $subItem) {
                        rec($subItem, $this);
                    }
                }
            } else {
                echo $this->getTrans('noDownloads');
            }
            ?>
        </ul>
    </div>
</div>
