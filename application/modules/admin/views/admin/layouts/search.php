<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('search') ?></legend>
<?php
$layoutsList = url_get_contents('http://ilch2.de/downloads/layouts/list.php');
$layoutsOnUpdateServer = json_decode($layoutsList);
$versionsOfLayouts = $this->get('versionsOfLayouts');

if (empty($layoutsOnUpdateServer)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($layoutsOnUpdateServer as $layoutOnUpdateServer): ?>
    <div id="layouts" class="col-lg-3 col-sm-6">
        <div class="panel panel-ilch">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <b><?=$this->escape($layoutOnUpdateServer->name) ?></b>
                    </div>
                    <div class="pull-right">
                        <?php if ($layoutOnUpdateServer->link != ''): ?>
                            <a href="<?=$layoutOnUpdateServer->link ?>" alt="<?=$this->escape($layoutOnUpdateServer->author) ?>" title="<?=$this->escape($layoutOnUpdateServer->author) ?>" target="_blank"><i><?=$this->escape($layoutOnUpdateServer->author) ?></i></a>
                        <?php else: ?>
                            <i><?=$this->escape($layoutOnUpdateServer->author) ?></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layoutOnUpdateServer->id]); ?>" title="<?=$this->getTrans('info') ?>">
                    <img src="<?=$layoutOnUpdateServer->thumbs[0]->img ?>" alt="<?=$this->escape($layoutOnUpdateServer->name) ?>" />
                </a>
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        $filename = basename($layoutOnUpdateServer->downloadLink);
                        $filename = strstr($filename,'.',true);
                        if (in_array($filename, $this->get('layouts')) && version_compare($versionsOfLayouts[$layoutOnUpdateServer->key], $layoutOnUpdateServer->version, '>=')): ?>
                            <span class="btn disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa fa-check text-success"></i>
                            </span>
                        <?php elseif (in_array($filename, $this->get('layouts')) && version_compare($versionsOfLayouts[$layoutOnUpdateServer->key], $layoutOnUpdateServer->version, '<')): ?>
                            <form method="POST" action="<?=$this->getUrl(['action' => 'update']) ?>">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        name="url"
                                        value="<?=$layoutOnUpdateServer->downloadLink ?>"
                                        title="<?=$this->getTrans('layoutUpdate') ?>">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        name="url"
                                        value="<?=$layoutOnUpdateServer->downloadLink ?>"
                                        title="<?=$this->getTrans('layoutDownload') ?>">
                                    <i class="fa fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layoutOnUpdateServer->id]); ?>" title="<?=$this->getTrans('info') ?>">
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
