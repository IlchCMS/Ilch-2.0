<link href="<?=$this->getModuleUrl('static/css/awards.css') ?>" rel="stylesheet">
<?php if ($this->get('awards') != ''): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="award">
                <div class="awards_info">
                    <?=$this->getTrans('currentlyThereAre') ?> <b><?=$this->get('awardsCount') ?></b> <?=$this->getTrans('awards') ?>.
                </div>
            </div>
        </div>
        <?php foreach ($this->get('awards') as $awards): ?>
            <div class="col-lg-6">
                <div class="award">
                    <div class="rank" align="center">
                        <?php if ($awards->getRank() == 1): ?>
                            <i class="fa fa-trophy img_gold" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"></i>
                        <?php elseif ($awards->getRank() == 2): ?>
                            <i class="fa fa-trophy img_silver" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"></i>
                        <?php elseif ($awards->getRank() == 3): ?>
                            <i class="fa fa-trophy img_bronze" title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"></i>
                        <?php else: ?>
                            <span title="<?=$this->getTrans('rank') ?> <?=$awards->getRank() ?>"><?=$awards->getRank() ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="rank_info">
                        <?=$awards->getSquad() ?><br />
                        <?=date('d.m.Y', strtotime($awards->getDate())) ?><br />
                        
                        <?php if ($awards->getEvent() != ''): ?>
                            <a href="<?=$this->escape($awards->getPage()) ?>" title="<?=$this->escape($awards->getEvent()) ?>" target="_blank"><?=$this->escape($awards->getEvent()) ?></a>
                        <?php else: ?>
                            <br />
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noAwards') ?>
<?php endif; ?>
