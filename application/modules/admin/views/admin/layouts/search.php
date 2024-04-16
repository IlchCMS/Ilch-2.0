<?php

/** @var \Ilch\View $this */

/** @var \Modules\Admin\Models\Layout[] $layouts */
$layouts = $this->get('layouts');

/** @var string $coreVersion */
$coreVersion = $this->get('coreVersion');

$layoutsList = url_get_contents($this->get('updateserver') . 'layouts2.php');
$layoutsOnUpdateServer = json_decode($layoutsList, true);
$cacheFilename = ROOT_PATH . '/cache/' . md5($this->get('updateserver') . 'layouts2.php') . '.cache';
$cacheFileDate = null;
if (file_exists($cacheFilename)) {
    $cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));
}
?>
<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('search') ?></h1>
<?php
if (empty($layoutsOnUpdateServer)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}
?>
<p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'search']) ?>" class="btn btn-primary"><?=$this->getTrans('searchForUpdates') ?></a> <?=(!empty($cacheFileDate)) ? '<span class="small">' . $this->getTrans('lastUpdateOn') . ' ' . $this->getTrans($cacheFileDate->format('l', true)) . $cacheFileDate->format(', d. ', true) . $this->getTrans($cacheFileDate->format('F', true)) . $cacheFileDate->format(' Y H:i', true) . '</span>' : $this->getTrans('lastUpdateOn') . ': ' . $this->getTrans('lastUpdateUnknown') ?></p>

<?php foreach ($layoutsOnUpdateServer as $layoutOnUpdateServer) : ?>
    <?php
        $layoutModel = new \Modules\Admin\Models\Layout();
        $layoutModel->setByArray($layoutOnUpdateServer);
    ?>
    <div id="layouts" class="col-lg-3 col-sm-6">
        <div class="panel panel-ilch">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <b><?=$this->escape($layoutOnUpdateServer['name']) ?></b>
                    </div>
                    <div class="pull-right">
                        <?php if ($layoutModel->getLink() != '') : ?>
                            <a href="<?=$layoutModel->getLink() ?>" alt="<?=$this->escape($layoutModel->getAuthor()) ?>" title="<?=$this->escape($layoutModel->getAuthor()) ?>" target="_blank" rel="noopener"><i><?=$this->escape($layoutModel->getAuthor()) ?></i></a>
                        <?php else : ?>
                            <i><?=$this->escape($layoutModel->getAuthor()) ?></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layoutOnUpdateServer['id']]) ?>" title="<?=$this->getTrans('info') ?>">
                    <img src="<?=$this->get('updateserver') . 'layouts/images/' . $layoutOnUpdateServer['thumbs'][0]['img'] ?>" alt="<?=$this->escape($layoutOnUpdateServer['name']) ?>" />
                </a>
                <?=(!empty($layoutOnUpdateServer['official']) && $layoutOnUpdateServer['official'] == true) ? '<span class="ilch-official">ilch</span>' : '' ?>
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        $layoutExists = $layouts[$layoutModel->getKey()] ?? null;

                        if ($layoutExists && !$layoutExists->isNewVersion($layoutModel->getVersion())) : ?>
                            <span class="btn disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa-solid fa-check text-success"></i>
                            </span>
                        <?php elseif ($layoutExists && $layoutExists->isNewVersion($layoutModel->getVersion())) : ?>
                            <?php if (!$layoutModel->hasCoreVersion($coreVersion)) : ?>
                                <button class="btn disabled"
                                        title="<?=$this->getTrans('ilchCoreError') ?>">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </button>
                            <?php else : ?>
                                <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $layoutModel->getKey(), 'version' => $layoutExists->getVersion(), 'newVersion' => $layoutModel->getVersion(), 'from' => 'search']) ?>">
                                    <?=$this->getTokenField() ?>
                                    <button type="submit"
                                            class="btn btn-default"
                                            title="<?=$this->getTrans('layoutUpdate') ?>">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php elseif (!$layoutModel->hasCoreVersion($coreVersion)) : ?>
                            <button class="btn disabled"
                                    title="<?=$this->getTrans('ilchCoreError') ?>">
                                <i class="fa-solid fa-download"></i>
                            </button>
                        <?php else : ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'search', 'key' => $layoutModel->getKey(), 'version' => $layoutModel->getVersion()]) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        title="<?=$this->getTrans('layoutDownload') ?>">
                                    <i class="fa-solid fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layoutOnUpdateServer['id']]) ?>" title="<?=$this->getTrans('info') ?>">
                            <span class="btn btn-default">
                                <i class="fa-solid fa-info text-info"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
