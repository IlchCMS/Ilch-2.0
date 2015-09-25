<link href="<?=$this->getModuleUrl('static/css/statistic.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>

<?php 
$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$userMapper = new \Modules\User\Mappers\User();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$date = new \Ilch\Date();
$dateCmsInstalled = new \Ilch\Date($this->get('dateCmsInstalled'));
$registNewUser = $userMapper->getUserById($this->get('registNewUser'));
?>

<legend><?=$this->getTrans('menuStatistic') ?></legend>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('siteStatistic') ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$dateCmsInstalled->format("Y-m-d", true) ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('siteOnlineSince') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center">
                                    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'index', 'action' => 'index')) ?>">
                                        <?=$this->get('registUserCount') ?>
                                    </a>
                                </h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalUsers') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12 col-lg-5">
                        <div class="panel status panel-default">
                            <div class="panel-heading">                                
                                <h1 class="panel-title text-center">
                                    <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $registNewUser->getId())) ?>">
                                        <?=$registNewUser->getName() ?>
                                    </a>
                                </h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('lastUser') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center">
                                    <a href="<?=$this->getUrl(array('module' => 'article', 'controller' => 'index', 'action' => 'index')) ?>">
                                        <?=$this->get('articlesCount') ?>
                                    </a>
                                </h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalArticles') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$this->get('commentsCount') ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalComments') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-offset-3 col-lg-3">
                        <div class="panel status panel-default">
                            <a href="http://ilch.de" target="_blank">
                                <div class="ilch-logo">
                                    <div class="panel-heading panel-ilch">
                                        <h1 class="panel-title ilch-title text-center">
                                            <?=$this->get('CMSVersion') ?>
                                        </h1>                                
                                    </div>
                                    <div class="panel-body ilch-body text-left">
                                        <strong><?=$this->getTrans('IlchCMSVersion') ?></strong>
                                    </div>                                    
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$this->get('visitsToday') ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statToday') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center">
                                    <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>">
                                        <?=$this->get('visitsMonth') ?>
                                    </a>
                                </h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statMonth') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center">
                                    <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>">
                                        <?=$this->get('visitsYear') ?>
                                    </a>
                                </h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statYear') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel status panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$this->get('visitsAllTotal') ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statTotal') ?></strong>
                            </div>
                        </div>
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
