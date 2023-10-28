<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('training') != ''):
    $displayed = 0;
    $adminAccess = null;
    if ($this->getUser()) {
        $adminAccess = $this->getUser()->isAdmin();
    }
    $trainingMapper = $this->get('trainingMapper');
    $entrantsMapper = $this->get('entrantsMapper');

    foreach ($this->get('training') as $training):
        if (!is_in_array($this->get('readAccess'), explode(',', $training->getReadAccess())) && $adminAccess == false) {
            continue;
        }
        $countdown = $trainingMapper->countdown(new \Ilch\Date($training->getDate()), $training->getTime());
        if ($countdown === false) {
            continue;
        }
        $displayed++;
        ?>
        <div class="nexttraining-box">
            <div class="row">
                <a href="<?=$this->getUrl('training/index/show/id/' . $training->getId()) ?>">
                    <div class="col-4 ellipsis" title="<?=$this->escape($training->getPlace()) ?>">
                        <div class="ellipsis-item">
                            <?=$this->escape($training->getTitle()) ?>
                        </div>
                    </div>
                    <div class="col-3 ellipsis">
                        <div class="ellipsis-item">
                            <?=count($entrantsMapper->getEntrantsById($training->getId())) ?>
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
    <?php if (!$displayed) : ?>
        <?=$this->getTrans('noTrainings') ?>
    <?php endif; ?>
<?php else: ?>
    <?=$this->getTrans('noTrainings') ?>
<?php endif; ?>
