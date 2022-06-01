<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('war') != ''):
    $displayed = 0;
    $adminAccess = null;
    if ($this->getUser()) {
        $adminAccess = $this->getUser()->isAdmin();
    }

    foreach ($this->get('war') as $war):
        $displayed++;

        $warMapper = $this->get('warMapper');  
        $gameImg = $this->getBoxUrl('static/img/'.$war->getWarGame().'.png');
        if (file_exists(APPLICATION_PATH.'/modules/war/static/img/'.$war->getWarGame().'.png')) {
            $gameImg = '<img src="'.$this->getBoxUrl('static/img/'.urlencode($war->getWarGame()).'.png').'" title="'.$this->escape($war->getWarGame()).'" width="16" height="16">';
        } else {
            $gameImg = '<i class="fa fa-question-circle text-muted" title="'.$this->escape($war->getWarGame()).'"></i>';
        }
        ?>
        <div class="nextwar-box">
            <div class="row">
                <a href="<?=$this->getUrl('war/index/show/id/' . $war->getId()) ?>" title="<?=$this->escape($war->getWarGroupTag()).' '.$this->getTrans('vs').' '.$this->escape($war->getWarEnemyTag()) ?>">
                    <div class="col-xs-4 ellipsis">
                        <?=$gameImg ?>
                        <div class="ellipsis-item">
                            <?=$this->escape($war->getWarGroupTag()) ?>
                        </div>
                    </div>
                    <div class="col-xs-2 small pull-left nextwar-vs"><?=$this->getTrans('vs') ?></div>
                    <div class="col-xs-3 ellipsis">
                        <div class="ellipsis-item">
                            <?=$this->escape($war->getWarEnemyTag()) ?>
                        </div>
                    </div>
                </a>
                <div class="col-xs-3 small nextwar-date">
                    <?php
                    $countdown = $warMapper->countdown($war->getWarTime());
                    ?>
                    <div class="ellipsis-item text-right" title="<?=$countdown ?>">
                        <i><?=$countdown ?></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$displayed) : ?>
        <?=$this->getTrans('noWars') ?>
    <?php endif; ?>
<?php else: ?>
    <?=$this->getTrans('noWars') ?>
<?php endif; ?>
