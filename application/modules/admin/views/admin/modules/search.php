<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('search') ?></legend>
<?php
$json = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$datas = json_decode($json);

if (empty($datas)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}
?>

<div id="modules" class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2" />
            <col class="col-lg-10" />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('modules') ?></th>
                <th><?=$this->getTrans('desc') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datas as $data):  ?>
                <tr>
                    <td>
                        <a href="<?=$this->getUrl(['action' => 'show', 'id' => $data->id]); ?>" title="<?=$this->getTrans('info') ?>"><?=$this->escape($data->name) ?></a>
                        <br />
                        <small>
                            <?=$this->getTrans('author')?>: 
                            <?php if ($data->link != ''): ?>
                                <a href="<?=$data->link ?>" alt="<?=$this->escape($data->author) ?>" title="<?=$this->escape($data->author) ?>" target="_blank"><i><?=$this->escape($data->author) ?></i></a>
                            <?php else: ?>
                                <i><?=$this->escape($data->author) ?></i>
                            <?php endif; ?>
                        </small>
                        <br /><br />
                        <?php
                        $filename = basename($data->downloadLink);
                        $filename = strstr($filename,'.',true);
                        if (in_array($filename, $this->get('modules'))): ?>
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
                                            title="<?=$this->getTrans('moduleDownload') ?>">
                                        <i class="fa fa-download fa-lg"></i>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <a href="<?=$this->getUrl(['action' => 'show', 'id' => $data->id]); ?>" title="<?=$this->getTrans('info') ?>">
                                <span class="btn btn-default">
                                    <i class="fa fa-info fa-lg text-info"></i>
                                </span>
                            </a>
                        </form>
                    </td>
                    <td><?=$data->desc ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
