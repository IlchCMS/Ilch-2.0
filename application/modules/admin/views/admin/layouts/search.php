<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('search') ?></legend>
<?php
$json = url_get_contents('http://ilch2.de/downloads/layouts/list.php');
$datas = json_decode($json);

if (empty($datas)) {
    echo $this->getTrans('noLayoutsAvailable');
    return;
}

foreach ($datas as $data): ?>
    <div id="layouts" class="col-lg-3 col-sm-6">
        <div class="panel panel-ilch">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <b><?=$this->escape($data->name) ?></b>
                    </div>
                    <div class="pull-right">
                        <?php if ($data->link != ''): ?>
                            <a href="<?=$data->link ?>" alt="<?=$this->escape($data->author) ?>" title="<?=$this->escape($data->author) ?>" target="_blank"><i><?=$this->escape($data->author) ?></i></a>
                        <?php else: ?>
                            <i><?=$this->escape($data->author) ?></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <a href="<?=$this->getUrl(['action' => 'show', 'id' => $data->id]); ?>" title="<?=$this->getTrans('info') ?>">
                    <img src="<?=$data->thumb ?>" alt="<?=$this->escape($data->name) ?>" />
                </a>
            </div>
            <div class="panel-footer">
                <div class="clearfix">
                    <div class="pull-left">
                        <?php
                        $filename = basename($data->downloadLink);
                        $filename = strstr($filename,'.',true);
                        if (in_array($filename, $this->get('layouts'))): ?>
                            <span class="btn btn-default disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                                <i class="fa fa-check fa-lg text-success"></i>
                            </span>
                        <?php else: ?>
                            <form method="POST" action="">
                                <?=$this->getTokenField() ?>
                                <button type="submit"
                                        class="btn btn-default"
                                        name="url"
                                        value="<?=$data->downloadLink ?>"
                                        title="<?=$this->getTrans('layoutDownload') ?>">
                                    <i class="fa fa-download fa-lg"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $data->id]); ?>" title="<?=$this->getTrans('info') ?>">
                            <span class="btn btn-default">
                                <i class="fa fa-info fa-lg text-info"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

