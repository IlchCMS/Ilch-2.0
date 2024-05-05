<?php

/** @var \Ilch\View $this */

$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$month = $this->getRequest()->getParam('month');
$year = $this->getRequest()->getParam('year');
$os = $this->getRequest()->getParam('os');
$browser = $this->getRequest()->getParam('browser');
?>

<link href="<?=$this->getModuleUrl('static/css/statistic.css') ?>" rel="stylesheet">

<?php if ($this->get('statisticOSVersionList') != '' && $os != '') : ?>
    <h1><?=$this->getTrans('menuStatistic') ?>: <i><?=$this->getTrans('osStatistic') ?></i></h1>
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('osStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>
                <div class="card-footer">
                    <?=$this->getTrans('os') ?>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticOSVersionList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $barLabel = ($statisticList->getOS()) ? $statisticList->getOS() . ' ' . $statisticList->getOSVersion() : $this->getTrans('unknown') ?>
                            <div class="list-group-item">
                                <strong>
                                    <?=$barLabel ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php elseif ($this->get('statisticBrowserVersionList') != '' && $browser != '') : ?>
    <h1><?=$this->getTrans('menuStatistic') ?>: <i><?=$this->getTrans('browserStatistic') ?> - <?=$this->escape($browser) ?></i></h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('browserStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>
                <div class="card-footer">
                    <?=$this->getTrans('browser') ?>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticBrowserVersionList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $barLabel = ($statisticList->getBrowser()) ? $statisticList->getBrowser() . ' ' . $statisticList->getBrowserVersion() : $this->getTrans('unknown') ?>
                            <div class="list-group-item">
                                <strong>
                                    <?=$barLabel ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php elseif ($this->get('statisticYearMonthDayList') != '' && $year != '' && $month != '') : ?>
    <?php $date = new \Ilch\Date($year . '-' . $month . '-01'); ?>
    <h1><?=$this->getTrans('menuStatistic') ?>: <i><?=$this->getTrans($date->format('F', true)) . $date->format(' Y', true) ?></i></h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>

                <div class="card-footer">
                    <h4 class="card-title"><?=$this->getTrans('hour') ?></h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticHourList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong><?=$statisticList->getDate() ?>:00 <?=$this->getTrans('clock') ?></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$statisticList->getDate() ?>:00 <?=$this->getTrans('clock') ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-footer">
                    <h4 class="panel-title"><?=$this->getTrans('day') ?></h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticDayList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                            <div class="list-group-item">
                                <strong><?=$this->getTrans($date->format('l')) ?></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$this->getTrans($date->format('l')) ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-footer">
                    <h4 class="panel-title"><?=$this->getTrans('yearMonthDay') ?></h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticYearMonthDayList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                            <div class="list-group-item">
                                <strong><?=$date->format('Y-m-d', true) ?></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$date->format('Y-m-d', true) ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-footer">
                    <h4 class="panel-title"><?=$this->getTrans('year') ?></h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticYearList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                            <div class="list-group-item">
                                <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true)]) ?>"><?=$date->format('Y', true) ?></a></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$date->format('Y', true) ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('browserStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>
                <div class="card-footer">
                    <?=$this->getTrans('browser') ?>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticBrowserList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $barLabel = ($statisticList->getBrowser() == '0') ? $this->getTrans('unknown') : $statisticList->getBrowser() ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getBrowser() == '0') : ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else : ?>
                                        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $year, 'month' => $month, 'browser' => $statisticList->getBrowser()]) ?>"><?=$statisticList->getBrowser() ?></a>
                                    <?php endif; ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticLanguageList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php if ($statisticList->getLang() == '') : ?>
                                <?=$this->getTrans('unknown') ?>
                                <?php $barLabel = $this->getTrans('unknown') ?>
                            <?php else : ?>
                                <?php $barLabel = $languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()) ?>
                                <?=$barLabel ?>
                            <?php endif; ?>
                            <div class="list-group-item">
                                <strong>
                                    <?=$barLabel ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="panel-title"><?=$this->getTrans('osStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>
                <div class="card-footer">
                    <?=$this->getTrans('os') ?>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticOSList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $barLabel = ''; ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if (!$statisticList->getOS()) : ?>
                                        <?=$this->getTrans('unknown') ?>
                                        <?php $barLabel = $this->getTrans('unknown'); ?>
                                    <?php else : ?>
                                        <?php if ($statisticList->getOS() == 'Windows') : ?>
                                            <?php $barLabel = $statisticList->getOS() . ' ' . $statisticList->getOSVersion(); ?>
                                            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $year, 'month' => $month, 'os' => $statisticList->getOS()]) ?>"><?=$barLabel ?></a>
                                        <?php else : ?>
                                            <?php $barLabel = $statisticList->getOS(); ?>
                                            <?=$barLabel ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php elseif ($this->get('statisticYearMonthList') != '' && $year != '' && $month == '' && $os == '') : ?>
    <?php $date = new \Ilch\Date($year . '-01-01'); ?>
    <h1><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('Y', true) ?></i></h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>

                <div class="card-footer">
                    <h4 class="card-title"><?=$this->getTrans('hour') ?></h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticHourList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong><?=$statisticList->getDate() ?>:00 <?=$this->getTrans('clock') ?></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$statisticList->getDate() ?>:00 <?=$this->getTrans('clock') ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                            <div class="list-group-item">
                                <strong><?=$this->getTrans($date->format('l')) ?></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$this->getTrans($date->format('l')) ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                            <?php $barLabel = $date->format('Y - ', true) . $this->getTrans($date->format('F', true)) ?>
                            <div class="list-group-item">
                                <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$barLabel ?></a></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                            <div class="list-group-item">
                                <strong><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $date->format('Y', true)]) ?>"><?=$date->format('Y', true) ?></a></strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$date->format('Y', true) ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('browserStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>
                <div class="card-footer">
                    <?=$this->getTrans('browser') ?>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticBrowserList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $barLabel = ($statisticList->getBrowser() == '0') ? $this->getTrans('unknown') : $statisticList->getBrowser() ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getBrowser() == '0') : ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else : ?>
                                        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $year, 'browser' => $statisticList->getBrowser()]) ?>"><?=$statisticList->getBrowser() ?></a>
                                    <?php endif; ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticLanguageList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php if ($statisticList->getLang() == '') : ?>
                                <?php $barLabel = $this->getTrans('unknown') ?>
                            <?php else : ?>
                                <?php $barLabel = $languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()) ?>
                            <?php endif; ?>
                            <div class="list-group-item">
                                <strong>
                                    <?=$barLabel ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="card-title"><?=$this->getTrans('osStatistic') ?></h4>
                    <span class="float-end clickable"><i class="fa-solid fa-chevron-up"></i></span>
                </div>
                <div class="card-footer">
                    <?=$this->getTrans('os') ?>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        /** @var Modules\Statistic\Models\Statistic $statisticList */
                        foreach ($this->get('statisticOSList') as $statisticList) : ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <?php $barLabel = ($statisticList->getOS()) ? $statisticList->getOS() . ' ' . $statisticList->getOSVersion() : $this->getTrans('unknown') ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if (!$statisticList->getOS()) : ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else : ?>
                                        <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'year' => $year, 'os' => $statisticList->getOS()]) ?>"><?=$barLabel ?></a>
                                    <?php endif; ?>
                                </strong>
                                <span class="float-end"><?=$statisticList->getVisits() ?></span>
                                <div class="radio">
                                    <div class="progress" role="progressbar" aria-label="<?=$barLabel ?>" aria-valuenow="<?=$progressWidth ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?=$progressWidth ?>%"></div>
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

<script>
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
