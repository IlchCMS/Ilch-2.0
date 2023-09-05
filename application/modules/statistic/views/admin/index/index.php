<?php

/** @var \Ilch\View $this */

/** @var Modules\Statistic\Models\Statisticconfig $statistic_config */
$statistic_config = $this->get('statistic_config');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <?php foreach ($statistic_config->configNames as $names) : ?>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError($names) ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans($names) ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="<?=$names ?>-on" name="<?=$names ?>" value="1" <?=$this->originalInput($names, $statistic_config->getConfigBy($names)) ? 'checked="checked"' : '' ?> />
                <label for="<?=$names ?>-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="<?=$names ?>-off" name="<?=$names ?>" value="0" <?=!$this->originalInput($names, $statistic_config->getConfigBy($names)) ? 'checked="checked"' : '' ?> />
                <label for="<?=$names ?>-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('#siteStatistic-off').click(function(){
        $('#ilchVersionStatistic-off').click();
        $('#modulesStatistic-off').click();
    });

    $('#ilchVersionStatistic-on').click(function(){
        $('#siteStatistic-on').click();
    });

    $('#modulesStatistic-on').click(function(){
        $('#siteStatistic-on').click();
    });
</script>
