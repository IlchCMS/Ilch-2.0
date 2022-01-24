<?php
$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$userMapper = new \Modules\User\Mappers\User();
$modulesMapper = new \Modules\Admin\Mappers\Module();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$date = new \Ilch\Date();
$dateCmsInstalled = new \Ilch\Date($this->get('dateCmsInstalled'));
$registNewUser = $userMapper->getUserById($this->get('registNewUser'));
$ilchVersionStatistic = $this->get('ilchVersionStatistic');
$modulesStatistic = $this->get('modulesStatistic');
$siteStatistic = $this->get('siteStatistic');
$visitsStatistic = $this->get('visitsStatistic');
$browserStatistic = $this->get('browserStatistic');
$osStatistic = $this->get('osStatistic');
?>

<link href="<?=$this->getModuleUrl('static/css/statistic.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuStatistic') ?></h1>
<?php if (!$siteStatistic && !$visitsStatistic && !$browserStatistic && !$osStatistic) : ?>
<?=$this->getTrans('everythingDisabled') ?>
<?php endif; ?>

<?php if ($siteStatistic) : ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('siteStatistic') ?></h4>
                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('siteOnlineSince') ?>: <?=$dateCmsInstalled->format('d.m.Y', true) ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center">
                                    <?=$dateCmsInstalled->format('d.m.Y', true) ?>
                                </span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('siteOnlineSince') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('totalUsers') ?>: <?=$this->get('registUserCount') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center">
                                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'index', 'action' => 'index']) ?>">
                                        <?=$this->get('registUserCount') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalUsers') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12 col-lg-5">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('lastUser') ?>: <?=$registNewUser->getName() ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center">
                                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $registNewUser->getId()]) ?>">
                                        <?=$this->escape($registNewUser->getName()) ?>
                                    </a>
                                </span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('lastUser') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('totalArticles') ?>: <?=$this->get('articlesCount') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center">
                                    <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'index']) ?>">
                                        <?=$this->get('articlesCount') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalArticles') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('totalComments') ?>: <?=$this->get('commentsCount') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center"><?=$this->get('commentsCount') ?></span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('totalComments') ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php if ($modulesStatistic) : ?>
                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('installedModules') ?>: <?=$this->get('modulesCount') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center"><?=$this->get('modulesCount') ?></span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('installedModules') ?></strong>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($ilchVersionStatistic) : ?>
                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('IlchCMSVersion') ?>: <?=$this->get('CMSVersion') ?>">
                            <a href="https://ilch.de" target="_blank" rel="noopener">
                                <div class="ilch-logo">
                                    <div class="panel-heading panel-ilch">
                                        <span class="panel-title ilch-title text-center">
                                            <?=$this->get('CMSVersion') ?>
                                        </span>
                                    </div>
                                    <div class="panel-body ilch-body text-left">
                                        <strong><?=$this->getTrans('IlchCMSVersion') ?></strong>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($modulesStatistic) : ?>
            <div class="panel-footer">
                <?=$this->getTrans('installedModules') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php foreach ($this->get('modules') as $modules):
                        if (!$modules->getSystemModule()):
                            $module = $modulesMapper->getModulesByKey($modules->getKey(), $this->getTranslator()->getLocale());
                            if (strncmp($modules->getIconSmall(), 'fa-', 3) === 0) {
                                $smallIcon = '<i class="fa '.$modules->getIconSmall().'"></i>';
                            } else {
                                $smallIcon = '<img src="'.$this->getStaticUrl('../application/modules/'.$modules->getKey().'/config/'.$modules->getIconSmall()).'" />';
                            }
                            ?>
                            <div class="col-xs-12 col-md-6 col-lg-3">
                                <div class="box">
                                    <div class="icon" title="<?=$this->getTrans('author') ?>: <?=$modules->getAuthor() ?>">
                                        <div class="image">
                                            <?=$smallIcon ?>
                                        </div>
                                        <div class="info">
                                            <h3 class="title"><strong><?=$this->escape($module->getName()) ?></strong></h3>
                                        </div>
                                    </div>
                                    <div class="space"></div>
                                </div> 
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($visitsStatistic) : ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center"><?=$this->get('visitsToday') ?></span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statToday') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center">
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>">
                                        <?=$this->get('visitsMonth') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statMonth') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center">
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true)]) ?>">
                                        <?=$this->get('visitsYear') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="panel-body text-center">
                                <strong><?=$this->getTrans('statYear') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="panel stats panel-default" title="<?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsAllTotal') ?>">
                            <div class="panel-heading">
                                <span class="panel-title text-center"><?=$this->get('visitsAllTotal') ?></span>
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
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong><?=$statisticList->getDate() ?>:00 <?=$this->getTrans('clock') ?></strong>
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
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><?=$this->getTrans($date->format('l')) ?></strong>
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
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><?=$date->format('Y-m-d', true) ?></strong>
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
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('Y - ', true).$this->getTrans($date->format('F', true)) ?></a></strong>
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
                    <?php foreach ($this->get('statisticYearList') as $statisticList): ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true)]) ?>"><?=$date->format('Y', true) ?></a></strong>
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
<?php endif; ?>

<?php if ($browserStatistic) : ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('browserStatistic') ?></h4>
                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="panel-footer">
                <?=$this->getTrans('browser') ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticBrowserList') as $statisticList): ?>
                        <?php $date = new \Ilch\Date(); ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if (!$statisticList->getBrowser()): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'browser' => $statisticList->getBrowser()]) ?>"><?=$statisticList->getBrowser() ?></a>
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
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if ($statisticList->getLang() == ''): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <?php $language = $languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()); ?>
                                    <?= ($language == '') ? $this->getTrans('unknown') : $language ?>
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
<?php endif; ?>

<?php if ($osStatistic) : ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->getTrans('osStatistic') ?></h4>
                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="panel-footer">
                <?=$this->getTrans('os') ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticOSList') as $statisticList): ?>
                        <?php $date = new \Ilch\Date(); ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if (!$statisticList->getOS()): ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'os' => $statisticList->getOS()]) ?>"><?=$statisticList->getOS().' '.$statisticList->getOSVersion() ?></a>
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
<?php endif; ?>

<script src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.progress .progress-bar').progressbar();
});

$(document).on('click', '.panel-heading span.clickable', function(e) {
    var $this = $(this);
    if (!$this.hasClass('panel-collapsed')) {
        $this.closest('.panel').find('.panel-body').slideUp();
        $this.addClass('panel-collapsed');
        $this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    } else {
        $this.closest('.panel').find('.panel-body').slideDown();
        $this.removeClass('panel-collapsed');
        $this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    }
})
</script>
