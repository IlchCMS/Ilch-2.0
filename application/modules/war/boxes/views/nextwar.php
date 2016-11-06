<link href="<?=$this->getBoxUrl('static/css/style.css') ?>" rel="stylesheet">

<?php if ($this->get('war') != ''):
    foreach ($this->get('war') as $war):        
        $warMapper = $this->get('warMapper');
        $warTime = $war->getWarTime();    
        $gameImg = $this->getBoxUrl('static/img/'.$war->getWarGame().'.png');
        if (file_exists(APPLICATION_PATH.'/modules/war/static/img/'.$war->getWarGame().'.png')) {
            $gameImg = '<img src="'.$this->getBoxUrl('static/img/'.$war->getWarGame().'.png').'" title="'.$this->escape($war->getWarGame()).'" width="16" height="16">';
        } else {
            $gameImg = '<i class="fa fa-question-circle text-muted" title="'.$war->getWarGame().'"></i>';
        }
        ?>
        <div class="nextwar-box">
            <div class="row">
                <a href="<?=$this->getUrl('war/index/show/id/' . $war->getId()) ?>">
                    <div class="col-xs-4 ellipsis">
                        <?=$gameImg ?>
                        <div class="ellipsis-item">
                            <?=$this->escape($war->getWarGroupTag()) ?>
                        </div>
                    </div>
                    <div class="col-xs-2 small pull-left nextwar-vs"><?=$this->getTrans('vs'); ?></div>
                    <div class="col-xs-3 ellipsis">
                        <div class="ellipsis-item">
                            <?=$this->escape($war->getWarEnemyTag()) ?>
                        </div>
                    </div>
                </a>
                <div class="col-xs-3 small nextwar-date">
                    <div class="ellipsis-item text-right" title="<?=$warMapper->countdown(date("Y", strtotime($warTime)), date("m", strtotime($warTime)), date("d", strtotime($warTime)), date("H", strtotime($warTime)), date("i", strtotime($warTime))) ?>">
                        <i><?=$warMapper->countdown(date("Y", strtotime($warTime)), date("m", strtotime($warTime)), date("d", strtotime($warTime)), date("H", strtotime($warTime)), date("i", strtotime($warTime))) ?></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTrans('noWars'); ?>
<?php endif; ?>
