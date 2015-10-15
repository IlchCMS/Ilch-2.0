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
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('siteOnlineSince') ?>: <?=$dateCmsInstalled->format("Y-m-d", true) ?>" style="cursor: help;">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$dateCmsInstalled->format("Y-m-d", true) ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('siteOnlineSince') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('totalUsers') ?>: <?=$this->get('registUserCount') ?>" style="cursor: help;">
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
                        <div class="panel stats panel-default" title="<?=$this->getTrans('lastUser') ?>: <?=$registNewUser->getName() ?>" style="cursor: help;">
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
                        <div class="panel stats panel-default" title="<?=$this->getTrans('totalArticles') ?>: <?=$this->get('articlesCount') ?>" style="cursor: help;">
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
                        <div class="panel stats panel-default" title="<?=$this->getTrans('totalComments') ?>: <?=$this->get('commentsCount') ?>" style="cursor: help;">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$this->get('commentsCount') ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalComments') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('installedModules') ?>: <?=$this->get('modulesCount') ?>" style="cursor: help;">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$this->get('modulesCount') ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('installedModules') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('IlchCMSVersion') ?>: <?=$this->get('CMSVersion') ?>">
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

            <div class="panel-footer">
                <?=$this->getTrans('installedModules') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php foreach ($this->get('modules') as $modules): ?>
                        <?php if(!$modules->getSystemModule()): ?>
                            <?php $modulesMapper = new \Modules\Admin\Mappers\Module(); ?>
                            <?php $module = $modulesMapper->getModulesByKey($modules->getKey(), $this->getTranslator()->getLocale()); ?>
                            <div class="col-xs-12 col-md-6 col-lg-3">
                                <div class="box">							
                                    <div class="icon" title="<?=$this->getTrans('author')?>: <?=$modules->getAuthor() ?>" style="cursor: help;">
                                        <div class="image">
                                            <img src="<?=$this->getStaticUrl('../application/modules/'.$modules->getKey().'/config/'.$modules->getIconSmall()) ?>" />
                                        </div>
                                        <div class="info">
                                            <h3 class="title"><strong><?=$module->getName() ?></strong></h3>
                                        </div>
                                    </div>
                                    <div class="space"></div>
                                </div> 
                            </div>
                        <?php endif; ?>
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
                <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday') ?>" style="cursor: help;">
                            <div class="panel-heading">
                                <h1 class="panel-title text-center"><?=$this->get('visitsToday') ?></h1>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statToday') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth') ?>" style="cursor: help;">
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
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear') ?>" style="cursor: help;">
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
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsAllTotal') ?>" style="cursor: help;">
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
                            <strong><?=$date->format("H") ?>:00 <?=$this->getTrans('clock') ?></strong>
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
                            <strong><?=$date->format("l") ?></strong>
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
                            <strong><?=$date->format("Y-m-d", true) ?></strong>
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
                            <strong><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$date->format("Y - F", true) ?></a></strong>
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
                            <strong><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$date->format("Y", true) ?></a></strong>
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
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-footer">
                <?=$this->getTrans('browser') ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticBrowserList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if ($statisticList->getBrowser() == '0'): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?=$statisticList->getBrowser() ?>
                                <?php endif; ?>
                            </strong>
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
                            <strong>
                                <?php if ($statisticList->getLang() == ''): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?=$languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()) ?>
                                <?php endif; ?>
                            </strong>
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
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-footer">
                <?=$this->getTrans('os') ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticOSList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if ($statisticList->getOS() == '0'): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?=$statisticList->getOS() ?>
                                <?php endif; ?>
                            </strong>
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

$(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
    if(!$this.hasClass('panel-collapsed')) {
        $this.closest('.panel').find('.panel-body').slideUp();
        $this.addClass('panel-collapsed');
        $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    } else {
        $this.closest('.panel').find('.panel-body').slideDown();
        $this.removeClass('panel-collapsed');
        $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    }
})
</script>
