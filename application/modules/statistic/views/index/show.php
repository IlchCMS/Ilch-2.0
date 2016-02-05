<link href="<?=$this->getModuleUrl('static/css/statistic.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>

<?php
$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$month = $this->getRequest()->getParam('month');
$year = $this->getRequest()->getParam('year');
$os = $this->getRequest()->getParam('os');
$browser = $this->getRequest()->getParam('browser');
?>

<?php if ($this->get('statisticOSVersionList') != '' AND $os != ''): ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$this->getTrans('osStatistic') ?> - Windows</i></legend>
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
                        <?php foreach ($this->get('statisticOSVersionList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getOS() == '0'): ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else: ?>
                                        <?=$statisticList->getOS() ?> <?=$statisticList->getOSVersion() ?>
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
<?php elseif ($this->get('statisticBrowserVersionList') != '' AND $browser != ''): ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$this->getTrans('osStatistic') ?> - <?=$browser ?></i></legend>
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
                        <?php foreach ($this->get('statisticBrowserVersionList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getBrowser() == '0'): ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else: ?>
                                        <?=$statisticList->getBrowser() ?> <?=$statisticList->getBrowserVersion() ?>
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
<?php elseif ($this->get('statisticYearMonthDayList') != '' AND $year != '' AND $month != ''): ?>
    <?php $date = new \Ilch\Date($year.'-'.$month.'-01'); ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('F Y', true) ?></i></legend>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </div>

                <div class="panel-footer">
                    <h4 class="panel-title"><?=$this->getTrans('hour') ?></h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach ($this->get('statisticHourList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                    <h4 class="panel-title"><?=$this->getTrans('year') ?></h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach ($this->get('statisticYearList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getBrowser() == '0'): ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else: ?>
                                        <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $year, 'month' => $month, 'browser' => $statisticList->getBrowser()))?>"><?=$statisticList->getBrowser() ?></a>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getOS() == '0'): ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else: ?>
                                        <?php if ($statisticList->getOS() == 'Windows'): ?>
                                            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $year, 'month' => $month, 'os' => $statisticList->getOS()))?>"><?=$statisticList->getOS() ?></a>
                                        <?php else: ?>
                                            <?=$statisticList->getOS() ?>
                                        <?php endif; ?>
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
<?php elseif ($this->get('statisticYearMonthList') != '' AND $year != '' AND $month == '' AND $os == ''): ?>
    <?php $date = new \Ilch\Date($year.'-01-01'); ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('Y', true) ?></i></legend>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"><?=$this->getTrans('visitsStatistic') ?></h4>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </div>

                <div class="panel-footer">
                    <h4 class="panel-title"><?=$this->getTrans('hour') ?></h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach ($this->get('statisticHourList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                    <h4 class="panel-title"><?=$this->getTrans('yearMonth') ?></h4>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach ($this->get('statisticYearMonthList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                        <?php foreach ($this->get('statisticYearList') as $statisticList): ?>
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getBrowser() == '0'): ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else: ?>
                                        <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $year, 'browser' => $statisticList->getBrowser()))?>"><?=$statisticList->getBrowser() ?></a>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
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
                            <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                            <div class="list-group-item">
                                <strong>
                                    <?php if ($statisticList->getOS() == '0'): ?>
                                        <?=$this->getTrans('unknown') ?>
                                    <?php else: ?>
                                        <?php if ($statisticList->getOS() == 'Windows'): ?>
                                            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $year, 'os' => $statisticList->getOS()))?>"><?=$statisticList->getOS() ?></a>
                                        <?php else: ?>
                                            <?=$statisticList->getOS() ?>
                                        <?php endif; ?>
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
