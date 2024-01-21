<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Mappers\Training $trainingMapper */
$trainingMapper = $this->get('trainingMapper');
/** @var \Modules\Training\Mappers\Entrants $entrantsMapper */
$entrantsMapper = $this->get('entrantsMapper');

/** @var \Modules\Training\Models\Training[]|null $training */
$training = $this->get('training');
?>
<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($training) : ?>
    <?php foreach ($training as $model) :
        $countdown = $trainingMapper->countdown(new \Ilch\Date($model->getDate()), $model->getTime());
        if ($countdown === false) {
            continue;
        }
        ?>
        <div class="nexttraining-box">
            <div class="row">
                <a href="<?=$this->getUrl('training/index/show/id/' . $model->getId()) ?>">
                    <div class="col-4 ellipsis" title="<?=$this->escape($model->getPlace()) ?>">
                        <div class="ellipsis-item">
                            <?=$this->escape($model->getTitle()) ?>
                        </div>
                    </div>
                    <div class="col-3 ellipsis">
                        <div class="ellipsis-item">
                            <?=count($entrantsMapper->getEntrantsById($model->getId()) ?? []) ?>
                        </div>
                    </div>
                </a>
                <div class="col-3 small nexttraining-date">
                    <div class="ellipsis-item text-right" title="<?=$countdown ?>">
                        <i><?=$countdown ?></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <?=$this->getTrans('noTrainings') ?>
<?php endif; ?>
