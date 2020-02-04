<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('search') ?></h1>
<?php
$layoutsList = url_get_contents($this->get('updateserver').'layouts.php');
$layoutsOnUpdateServer = json_decode($layoutsList);
$versionsOfLayouts = $this->get('versionsOfLayouts');
$cacheFilename = ROOT_PATH.'/cache/'.md5($this->get('updateserver').'layouts.php').'.cache';
$cacheFileDate = new \Ilch\Date(date('Y-m-d H:i:s.', filemtime($cacheFilename)));

if (empty($layoutsOnUpdateServer)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}
?>
<p><a href="<?=$this->getUrl(['action' => 'refreshurl', 'from' => 'search']) ?>" class="btn btn-primary"><?=$this->getTrans('updateNow') ?></a> <span class="small"><?=$this->getTrans('lastUpdateOn') ?> <?=$this->getTrans($cacheFileDate->format('l', true)).$cacheFileDate->format(', d. ', true).$this->getTrans($cacheFileDate->format('F', true)).$cacheFileDate->format(' Y H:i', true) ?></span></p>

<?php foreach ($layoutsOnUpdateServer as $layoutOnUpdateServer): ?>
    <div id="layouts" class="col-lg-3 col-sm-6">
        <div class="panel panel-ilch">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <b><?=$this->escape($layoutOnUpdateServer->name) ?></b>
                    </div>
                    <div class="pull-right">
                        <?php if ($layoutOnUpdateServer->link != ''): ?>
                            <a href="<?=$layoutOnUpdateServer->link ?>" alt="<?=$this->escape($layoutOnUpdateServer->author) ?>" title="<?=$this->escape($layoutOnUpdateServer->author) ?>" target="_blank" rel="noopener"><i><?=$this->escape($layoutOnUpdateServer->author) ?></i></a>
                        <?php else: ?>
                            <i><?=$this->escape($layoutOnUpdateServer->author) ?></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layoutOnUpdateServer->id]) ?>" title="<?=$this->getTrans('info') ?>">
                    <img src="<?=$this->get('updateserver').'layouts/images/'.$layoutOnUpdateServer->thumbs[0]->img ?>" alt="<?=$this->escape($layoutOnUpdateServer->name) ?>" />
                </a>
                <?=(!empty($layoutOnUpdateServer->official) && $layoutOnUpdateServer->official) ? '<span class="ilch-official">ilch</span>' : '' ?>
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        if (in_array($layoutOnUpdateServer->key, $this->get('layouts')) && version_compare($versionsOfLayouts[$layoutOnUpdateServer->key], $layoutOnUpdateServer->version, '>=')): ?>
                            <span class="btn disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa fa-check text-success"></i>
                            </span>
                        <?php elseif (in_array($layoutOnUpdateServer->key, $this->get('layouts')) && version_compare($versionsOfLayouts[$layoutOnUpdateServer->key], $layoutOnUpdateServer->version, '<')): ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'update', 'key' => $layoutOnUpdateServer->key, 'version' => $versionsOfLayouts[$layoutOnUpdateServer->key], 'newVersion' => $layoutOnUpdateServer->version, 'from' => 'search']) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        title="<?=$this->getTrans('layoutUpdate') ?>">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'search', 'key' => $layoutOnUpdateServer->key, 'version' => $layoutOnUpdateServer->version]) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        title="<?=$this->getTrans('layoutDownload') ?>">
                                    <i class="fa fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layoutOnUpdateServer->id]) ?>" title="<?=$this->getTrans('info') ?>">
                            <span class="btn btn-default">
                                <i class="fa fa-info text-info"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
