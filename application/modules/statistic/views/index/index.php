<?php

/** @var \Ilch\View $this */

$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$modulesMapper = new \Modules\Admin\Mappers\Module();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$date = new \Ilch\Date();
$dateCmsInstalled = new \Ilch\Date($this->get('dateCmsInstalled'));
$registNewUser = $this->get('registNewUser');

/** @var Modules\Statistic\Models\Statisticconfig $statistic_config */
$statistic_config = $this->get('statistic_config');
?>
<link href="<?=$this->getModuleUrl('static/css/statistic.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.min.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuStatistic') ?></h1>
<?php if ($statistic_config->isDisabled()) : ?>
    <?=$this->getTrans('everythingDisabled') ?>
<?php endif; ?>

<?php if ($statistic_config->getSiteStatistic()) : ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card border-primary">
            <div class="card-header bg-primary">
                <h4 class="card-title"><?=$this->getTrans('siteStatistic') ?></h4>
                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6 col-xl-4">
                        <div class="card stats card-default" title="<?=$this->getTrans('siteOnlineSince') ?>: <?=$dateCmsInstalled->format('d.m.Y', true) ?>">
                            <div class="card-header text-center">
                                <span class="card-title">
                                    <?=$dateCmsInstalled->format('d.m.Y', true) ?>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('siteOnlineSince') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('totalUsers') ?>: <?=$this->get('registUserCount') ?>">
                            <div class="card-header text-center">
                                <span class="card-title">
                                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'index', 'action' => 'index']) ?>">
                                        <?=$this->get('registUserCount') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('totalUsers') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-12 col-xl-5">
                        <div class="card stats card-default" title="<?=$this->getTrans('lastUser') ?>: <?=$registNewUser->getName() ?>">
                              <div class="card-header text-center">
                                  <span class="card-title">
                                    <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $registNewUser->getId()]) ?>">
                                        <?=$this->escape($registNewUser->getName()) ?>
                                    </a>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('lastUser') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('totalArticles') ?>: <?=$this->get('articlesCount') ?>">
                            <div class="card-header text-center">
                                <span class="card-title">
                                    <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'index']) ?>">
                                        <?=$this->get('articlesCount') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('totalArticles') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('totalComments') ?>: <?=$this->get('commentsCount') ?>">
                            <div class="card-header text-center">
                                <span class="card-title"><?=$this->get('commentsCount') ?></span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('totalComments') ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php if ($statistic_config->getModulesStatistic()) : ?>
                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('installedModules') ?>: <?=$this->get('modulesCount') ?>">
                            <div class="card-header text-center">
                                <span class="card-title"><?=$this->get('modulesCount') ?></span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('installedModules') ?></strong>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($statistic_config->getIlchVersionStatistic()) : ?>
                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('IlchCMSVersion') ?>: <?=$this->get('CMSVersion') ?>">
                            <a href="https://ilch.de" target="_blank" rel="noopener">
                                <div class="ilch-logo">
                                    <div class="card-header text-center card-ilch">
                                        <span class="card-title ilch-title">
                                            <?=$this->get('CMSVersion') ?>
                                        </span>
                                    </div>
                                    <div class="card-body ilch-body text-left">
                                        <strong><?=$this->getTrans('IlchCMSVersion') ?></strong>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($statistic_config->getModulesStatistic()) : ?>
            <div class="card-footer">
                <?=$this->getTrans('installedModules') ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    /** @var Modules\Admin\Models\Module $modules */
                    foreach ($this->get('modules') as $modules) :
                        if (!$modules->getSystemModule()) :
                            $module = $modulesMapper->getModulesByKey($modules->getKey(), $this->getTranslator()->getLocale());
                            if (strncmp($modules->getIconSmall(), 'fa-', 3) === 0) {
                                $smallIcon = '<i class="fa ' . $modules->getIconSmall() . '"></i>';
                            } else {
                                $smallIcon = '<img src="' . $this->getStaticUrl('../application/modules/' . $modules->getKey() . '/config/' . $modules->getIconSmall()) . '" />';
                            }
                            ?>
                            <div class="col-12 col-lg-6 col-xl-3">
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

<?php if ($statistic_config->getVisitsStatistic()) : ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card border-primary">
            <div class="card-header bg-primary">
                <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                <span class="float-end clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday') ?>">
                            <div class="card-header text-center">
                                <span class="card-title"><?=$this->get('visitsToday') ?></span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('statToday') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth') ?>">
                            <div class="card-header text-center">
                                <span class="card-title">
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>">
                                        <?=$this->get('visitsMonth') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('statMonth') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear') ?>">
                            <div class="card-header text-center">
                                <span class="card-title">
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true)]) ?>">
                                        <?=$this->get('visitsYear') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('statYear') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xl-3">
                        <div class="card stats card-default" title="<?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsAllTotal') ?>">
                            <div class="card-header text-center">
                                <span class="panel-title"><?=$this->get('visitsAllTotal') ?></span>
                            </div>
                            <div class="card-body text-center">
                                <strong><?=$this->getTrans('statTotal') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <h4 class="card-title"><?=$this->getTrans('hour') ?></h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    /** @var Modules\Statistic\Models\Statistic $statisticList */
                    foreach ($this->get('statisticHourList') as $statisticList) : ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong><?=$statisticList->getDate() ?>:00 <?=$this->getTrans('clock') ?></strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <h4 class="card-title"><?=$this->getTrans('day') ?></h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    /** @var Modules\Statistic\Models\Statistic $statisticList */
                    foreach ($this->get('statisticDayList') as $statisticList) : ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><?=$this->getTrans($date->format('l')) ?></strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <h4 class="card-title"><?=$this->getTrans('yearMonthDay') ?></h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    /** @var Modules\Statistic\Models\Statistic $statisticList */
                    foreach ($this->get('statisticYearMonthDayList') as $statisticList) : ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><?=$date->format('Y-m-d', true) ?></strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <h4 class="card-title"><?=$this->getTrans('yearMonth') ?></h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    /** @var Modules\Statistic\Models\Statistic $statisticList */
                    foreach ($this->get('statisticYearMonthList') as $statisticList) : ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('Y - ', true) . $this->getTrans($date->format('F', true)) ?></a></strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <h4 class="card-title"><?=$this->getTrans('year') ?></h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    /** @var Modules\Statistic\Models\Statistic $statisticList */
                    foreach ($this->get('statisticYearList') as $statisticList) : ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                        <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                        <div class="list-group-item">
                            <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true)]) ?>"><?=$date->format('Y', true) ?></a></strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
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

<?php if ($statistic_config->getBrowserStatistic()) : ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card border-primary">
            <div class="card-header bg-primary">
                <h4 class="panel-title"><?=$this->getTrans('browserStatistic') ?></h4>
                <span class="float-end clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="card-footer">
                <?=$this->getTrans('browser') ?>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    /** @var Modules\Statistic\Models\Statistic $statisticList */
                    foreach ($this->get('statisticBrowserList') as $statisticList) : ?>
                        <?php $date = new \Ilch\Date(); ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if (!$statisticList->getBrowser()) : ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else : ?>
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'browser' => $statisticList->getBrowser()]) ?>"><?=$statisticList->getBrowser() ?></a>
                                <?php endif; ?>
                            </strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
                                    <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <h4 class="card-title"><?=$this->getTrans('language') ?></h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticLanguageList') as $statisticList) : ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if ($statisticList->getLang() == '') : ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else : ?>
                                    <?php $language = $languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()); ?>
                                    <?= ($language == '') ? $this->getTrans('unknown') : $language ?>
                                <?php endif; ?>
                            </strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
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

<?php if ($statistic_config->getOsStatistic()) : ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card border-primary">
            <div class="card-header bg-primary">
                <h4 class="panel-title"><?=$this->getTrans('osStatistic') ?></h4>
                <span class="float-end clickable"><i class="fa fa-chevron-up"></i></span>
            </div>
            <div class="card-footer">
                <?=$this->getTrans('os') ?>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($this->get('statisticOSList') as $statisticList) : ?>
                        <?php $date = new \Ilch\Date(); ?>
                        <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                        <div class="list-group-item">
                            <strong>
                                <?php if (!$statisticList->getOS()) : ?>
                                    <?=$this->getTrans('unknown') ?>
                                <?php else : ?>
                                    <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'os' => $statisticList->getOS()]) ?>"><?=$statisticList->getOS() . ' ' . $statisticList->getOSVersion() ?></a>
                                <?php endif; ?>
                            </strong>
                            <span class="float-end"><?=$statisticList->getVisits() ?></span>
                            <div class="radio">
                                <div class="progress" style="margin-bottom: 0;">
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

$(document).on('click', '.card-header span.clickable', function() {
    if (!$(this).hasClass('panel-collapsed')) {
        $(this).closest('.card').find('.card-body').slideUp();
        $(this).addClass('panel-collapsed');
        $(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    } else {
        $(this).closest('.card').find('.card-body').slideDown();
        $(this).removeClass('panel-collapsed');
        $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    }
})
</script>
