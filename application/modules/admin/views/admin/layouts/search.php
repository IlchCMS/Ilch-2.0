<?php

$json = file_get_contents('http://wcc-karneval.de/test.php');
$datas = json_decode($json);
foreach ($datas as $data) : ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="product-template" itemscope="" itemtype="http://schema.org/Product">
                <figure>
                <img class="img-responsive" src="<?=$data->thumb?>" alt="Corlate  - Free Responsive Business HTML Template" itempro="image" width="100%">
                </figure>
                <form method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <button type = "submit" class = "btn btn-default" name="url" value="<?=$data->downloadLink?>">Install <?=$data->name?>?</button>
                </form>
            </div>
        </div>
    </div>
    
<?php endforeach; ?>
<style>
    
</style>