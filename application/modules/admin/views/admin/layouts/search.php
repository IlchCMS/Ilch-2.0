<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('search') ?></legend>
<?php
$layoutsList = url_get_contents('http://ilch2.de/downloads/layouts/list.php');
$layouts = json_decode($layoutsList);

if (empty($layouts)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($layouts as $layout): ?>
    <div id="layouts" class="col-lg-3 col-sm-6">
        <div class="panel panel-ilch">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <b><?=$this->escape($layout->name) ?></b>
                    </div>
                    <div class="pull-right">
                        <?php if ($layout->link != ''): ?>
                            <a href="<?=$layout->link ?>" alt="<?=$this->escape($layout->author) ?>" title="<?=$this->escape($layout->author) ?>" target="_blank"><i><?=$this->escape($layout->author) ?></i></a>
                        <?php else: ?>
                            <i><?=$this->escape($layout->author) ?></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layout->id]); ?>" title="<?=$this->getTrans('info') ?>">
                    <img src="<?=$layout->thumb ?>" class="img-thumbnail" alt="<?=$this->escape($layout->name) ?>" />
                </a>
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        $filename = basename($layout->downloadLink);
                        $filename = strstr($filename,'.',true);
                        if (in_array($filename, $this->get('layouts'))): ?>
                            <span class="btn btn-default disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa fa-check text-success"></i>
                            </span>
                        <?php else: ?>
                            <form method="POST" action="">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        name="url"
                                        value="<?=$layout->downloadLink ?>"
                                        title="<?=$this->getTrans('layoutDownload') ?>">
                                    <i class="fa fa-download"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $layout->id]); ?>" title="<?=$this->getTrans('info') ?>">
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

