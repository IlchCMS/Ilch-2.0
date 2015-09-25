<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>

<style>
.panel-body .list-group {
    margin-bottom: 0;
}
.panel-body .progress {
    margin-bottom: 0px;
}

.progress-bar {
    -webkit-transition: width 4s ease-in-out;
    -moz-transition: width 4s ease-in-out;
    -ms-transition: width 4s ease-in-out;
    -o-transition: width 4s ease-in-out;
    transition: width 4s ease-in-out;
}
</style>

<?php 
$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$date = new \Ilch\Date();
?>

<legend><?=$this->getTrans('menuStatistic') ?></legend>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3">
                        <?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday') ?>
                    </div>
                    <div class="col-lg-3">
                        <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth') ?></a>
                    </div>
                    <div class="col-lg-3">
                        <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear') ?></a>
                    </div>
                    <div class="col-lg-3">
                        <?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsAllTotal') ?>
                    </div>
                </div>
            </div>
            
            <div class="panel-footer">
                <h4 class="panel-title"><?=$this->getTrans('hour') ?></h4>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticHourList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <b><?=$date->format("H") ?>:00 <?=$this->getTrans('clock') ?></b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="panel-footer">
                <h4 class="panel-title"><?=$this->getTrans('day') ?></h4>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticDayList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <b><?=$date->format("l") ?></b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="panel-footer">
                <h4 class="panel-title"><?=$this->getTrans('yearMonthDay') ?></h4>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticYearMonthDayList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <b><?=$date->format("Y-m-d", true) ?></b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="panel-footer">
                <h4 class="panel-title"><?=$this->getTrans('yearMonth') ?></h4>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticYearMonthList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <b><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$date->format("Y - F", true) ?></a></b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="panel-footer">
                <h4 class="panel-title"><?=$this->getTrans('year') ?></h4>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticYearMonthList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <b><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$date->format("Y", true) ?></a></b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('browserStatistic') ?></h4>
            </div>
            <div class="panel-footer">
                <?=$this->getTrans('browser') ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticBrowserList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <div class="list-group-item">
                            <b>
                                <?php if ($statisticList->getBrowser() == '0'): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?=$statisticList->getBrowser() ?>
                                <?php endif; ?>
                            </b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="panel-footer">
                <h4 class="panel-title"><?=$this->getTrans('language') ?></h4>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticLanguageList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <div class="list-group-item">
                            <b>
                                <?php if ($statisticList->getLang() == ''): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?=$languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()) ?>
                                <?php endif; ?>
                            </b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('osStatistic') ?></h4>
            </div>
            <div class="panel-footer">
                <?=$this->getTrans('os') ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticOSList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <div class="list-group-item">
                            <b>
                                <?php if ($statisticList->getOS() == '0'): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?=$statisticList->getOS() ?>
                                <?php endif; ?>
                            </b>
                            <span class="pull-right"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.progress .progress-bar').progressbar();
});
</script>
