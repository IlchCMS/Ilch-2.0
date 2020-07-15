<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('training') != ''):
    $displayed = 0;
    $adminAccess = null;
    if ($this->getUser()) {
        $adminAccess = $this->getUser()->isAdmin();
    }

    foreach ($this->get('training') as $training):
        if (!is_in_array($this->get('readAccess'), explode(',', $training->getReadAccess())) && $adminAccess == false) {
            continue;
        }
        $displayed++;

        $trainingMapper = $this->get('trainingMapper');
        $entrantsMapper = $this->get('entrantsMapper');
        $trainingTime = $training->getDate();    
        ?>
        <div class="nexttraining-box">
            <div class="row">
                <a href="<?=$this->getUrl('training/index/show/id/' . $training->getId()) ?>">
                    <div class="col-xs-4 ellipsis">
                        <div class="ellipsis-item">
                            <?=$this->escape($training->getTitle()) ?>
                        </div>
                    </div>
                    <div class="col-xs-3 ellipsis">
                        <div class="ellipsis-item">
                            <?=count($entrantsMapper->getEntrantsById($training->getId())) ?>
                        </div>
                    </div>
                </a>
                <div class="col-xs-3 small nexttraining-date">
                    <div class="ellipsis-item text-right" title="<?=$trainingMapper->countdown(date("Y", strtotime($trainingTime)), date("m", strtotime($trainingTime)), date("d", strtotime($trainingTime)), date("H", strtotime($trainingTime)), date("i", strtotime($trainingTime))) ?>">
                        <i><?=$trainingMapper->countdown(date("Y", strtotime($trainingTime)), date("m", strtotime($trainingTime)), date("d", strtotime($trainingTime)), date("H", strtotime($trainingTime)), date("i", strtotime($trainingTime))) ?></i>
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
