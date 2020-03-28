<?php $historys = $this->get('historys'); ?>

<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/history.css') ?>">

<h1><?=$this->getTrans('menuHistorys') ?></h1>
<?php if ($historys != ''): ?>
    <section id="cd-timeline" class="cd-container">
        <?php foreach ($historys as $history): ?>
            <div class="cd-timeline-block">
                <div class="cd-timeline-img" style="background: <?=$history->getColor() ?>;">
                    <?=($history->getType() != '') ? '<i class="'.$history->getType().' fa-2x"></i>' : '' ?>
                </div>

                <div class="cd-timeline-content">
                    <h3 id="history-<?=$history->getId() ?>"><a href="<?=$this->getUrl(['action' => 'index']) ?>#history-<?=$history->getId() ?>"><?=$this->escape($history->getTitle()) ?></a></h3>
                    <?=$this->purify($history->getText()) ?>
                    <?php $getDate = new \Ilch\Date($history->getDate()); ?>
                    <span class="cd-date"><?=$getDate->format('d. ', true).$this->getTrans($getDate->format('F', true)).$getDate->format(' Y', true) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
<?php else: ?>
    <?=$this->getTrans('noHistorys') ?>
<?php endif; ?>

<script src="<?=$this->getModuleUrl('static/js/modernizr.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/main.js') ?>"></script>
