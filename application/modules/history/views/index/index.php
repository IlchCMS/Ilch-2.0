<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/history.css') ?>">

<?php $historys = $this->get('historys'); ?>
<legend><?=$this->getTrans('menuHistorys') ?></legend>
<?php if ($historys != ''): ?>
	<section id="cd-timeline" class="cd-container">
        <?php foreach ($this->get('historys') as $history): ?>
            <div class="cd-timeline-block">
                <div class="cd-timeline-img" style="background: <?=$history->getColor() ?>;">
                    <?php 
                        if ($history->getTyp() != '') {
                            echo '<img src="'.$this->getModuleUrl('static/img/'.$history->getTyp().'.png').'" alt="">';
                        }
                    ?>
                </div>

                <div class="cd-timeline-content">
                    <h3><?=$this->escape($history->getTitle()) ?></h3>
                    <?=$history->getText() ?>
                    <?php $getDate = new \Ilch\Date($history->getDate()); ?>
                    <span class="cd-date"><?=$getDate->format('d. F Y', true) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
	</section>
<?php else: ?>
    <?=$this->getTrans('noHistorys') ?>
<?php endif; ?>

<script src="<?=$this->getModuleUrl('static/js/modernizr.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/main.js') ?>"></script>
