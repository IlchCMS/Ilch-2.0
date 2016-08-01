<legend><?=$this->getTrans('search') ?></legend>
<?php
$json = url_get_contents('http://ilch2.de/downloads/modules/list.php');
$datas = json_decode($json);

if (empty($datas)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}

foreach ($datas as $data): ?>
    <div class="row">
        <div class="col-sm-4">
            <div class="product-template" itemscope="" itemtype="http://schema.org/Product">
                <figure>
                    <img class="img-responsive" src="<?=$data->thumb?>" alt="<?=$data->name?>" itempro="image" width="100%">
                </figure>
                <form method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <button type = "submit" class = "btn btn-default" name="url" value="<?=$data->downloadLink?>">Install <?=$data->name?>?</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
