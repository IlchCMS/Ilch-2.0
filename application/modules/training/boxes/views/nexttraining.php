<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Mappers\Training $trainingMapper */
$trainingMapper = $this->get('trainingMapper');
/** @var \Modules\Training\Mappers\Entrants $entrantsMapper */
$entrantsMapper = $this->get('entrantsMapper');

/** @var \Modules\Training\Models\Training[]|null $trainings */
$trainings = $this->get('trainings');
?>
<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($trainings) : ?>
    <div class="nexttraining-box">
    <?php foreach ($trainings as $model) :
        $countdown = $trainingMapper->countdown(new \Ilch\Date($model->getDate()));
        if ($countdown === false) {
            continue;
        }
        ?>
        <div class="row">
            <div class="col-9">
                <a href="<?=$this->getUrl('training/index/show/id/' . $model->getId()) ?>">
                    <div class="row">
                        <div class="col-7 ellipsis" title="<?=$this->escape($model->getPlace()) ?>">
                            <div class="ellipsis-item">
                                <?=$this->escape($model->getTitle()) ?>
                            </div>
                        </div>
                        <div class="col-5 ellipsis">
                            <div class="ellipsis-item">
                                <?=count($entrantsMapper->getEntrantsById($model->getId()) ?? []) . ' ' . $this->getTrans('entrant') ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3 small nexttraining-date">
                <div class="ellipsis-item text-end" title="<?=$countdown ?>">
                    <i><?=$countdown ?></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php else : ?>
    <?=$this->getTrans('noTrainings') ?>
<?php endif; ?>
