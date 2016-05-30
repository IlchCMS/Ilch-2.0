<?php if ($this->get('war') != ''): 
    foreach ($this->get('war') as $war):        
        $warMapper = $this->get('warMapper');
        $warTime = $war->getWarTime();    
        $gameImg = $this->getBaseUrl('application/modules/war/static/img/'.$war->getWarGame().'.png');
        if ($warMapper->url_check($gameImg)) {
            $gameImg = '<img src="'.$this->getBaseUrl('application/modules/war/static/img/'.$war->getWarGame().'.png').'" title="'.$this->escape($war->getWarGame()).'" width="16" height="16">';
        } else {
            $gameImg = '<i class="fa fa-question-circle text-muted"></i>';        
        }
        ?>
        <div class="games-schedule-items">
            <div class="row games-team">
                <a href="<?=$this->getUrl('war/index/show/id/' . $war->getId()) ?>">                
                    <div class="col-md-5">
                        <div style="width: 20px; float: left;"><?=$gameImg ?></div>
                        <div><?=$this->escape($war->getWarEnemyTag()) ?></div>
                    </div>
                    <div class="small" style="margin-top: 3px; float: left;">vs.</div>
                    <div class="col-md-3" style="padding-left: 5px;">
                        <?=$this->escape($war->getWarGroupTag()) ?>
                    </div>
                </a>
                <div class="small" style="margin-top: 3px; margin-right: 3px;" align="right">
                    <?=$warMapper->countdown(date("Y", strtotime($warTime)), date("m", strtotime($warTime)), date("d", strtotime($warTime)), date("H", strtotime($warTime)), date("i", strtotime($warTime))) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTranslator()->trans('noWars'); ?>
<?php endif; ?>
