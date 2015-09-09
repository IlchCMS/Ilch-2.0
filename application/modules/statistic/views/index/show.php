<link href="<?=$this->getModuleUrl('static/css/bootstrap-progressbar-3.3.4.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/bootstrap-progressbar.js') ?>"></script>

<style>
.progress-bar {
    -webkit-transition: width 4s ease-in-out;
    -moz-transition: width 4s ease-in-out;
    -ms-transition: width 4s ease-in-out;
    -o-transition: width 4s ease-in-out;
    transition: width 4s ease-in-out;
}
</style>

<?php
$StatisticMapper = new \Modules\Statistic\Mappers\Statistic();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$month = $this->getRequest()->getParam('month');
$year = $this->getRequest()->getParam('year');
?>

<?php if ($this->get('statisticYearMonthDayList') != '' AND $year != '' AND $month != ''): ?>
    <?php $date = new \Ilch\Date($year.'-'.$month.'-01'); ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('F Y', true) ?></i></legend>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('hour') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticHourList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><?=$date->format("H") ?>:00 <?=$this->getTrans('clock') ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('day') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticDayList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><?=$date->format("l") ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('yearMonthDay') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticYearMonthDayList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><?=$date->format("Y-m-d", true) ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('year') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticYearList') as $statisticList): ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$date->format("Y", true) ?></a></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="100">
                                    100%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="3"><?=$this->getTrans('browserStatistic') ?></th>
                </tr>
                <tr>
                    <th colspan="2"><?=$this->getTrans('browser') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticBrowserList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <tr>
                        <td>
                            <?php if ($statisticList->getBrowser() == '0'): ?>
                                <?=$this->getTrans('unknown') ?>
                            <?php else: ?>
                                <?=$statisticList->getBrowser() ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('language') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticLanguageList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <tr>
                        <td>
                            <?php if ($statisticList->getLang() == ''): ?>
                                <?=$this->getTrans('unknown') ?>
                            <?php else: ?>
                                <?=$languageCodes->statisticLanguage($statisticList->getLang(), $this->getTranslator()->getLocale()) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="3"><?=$this->getTrans('osStatistic') ?></th>
                </tr>
                <tr>
                    <th colspan="2"><?=$this->getTrans('os') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticOSList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <tr>
                        <td>
                            <?php if ($statisticList->getOS() == '0'): ?>
                                <?=$this->getTrans('unknown') ?>
                            <?php else: ?>
                                <?=$statisticList->getOS() ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($this->get('statisticYearMonthList') != '' AND $year != ''): ?>
    <?php $date = new \Ilch\Date($year.'-01-01'); ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('Y', true) ?></i></legend>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('hour') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticHourList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><?=$date->format("H") ?>:00 <?=$this->getTrans('clock') ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('day') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticDayList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><?=$date->format("l") ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('yearMonth') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticYearMonthList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$date->format("Y - F", true) ?></a></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('year') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticYearList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                    <tr>
                        <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$date->format("Y", true) ?></a></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="3"><?=$this->getTrans('browserStatistic') ?></th>
                </tr>
                <tr>
                    <th colspan="2"><?=$this->getTrans('browser') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticBrowserList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <tr>
                        <td>
                            <?php if ($statisticList->getBrowser() == '0'): ?>
                                <?=$this->getTrans('unknown') ?>
                            <?php else: ?>
                                <?=$statisticList->getBrowser() ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2"><?=$this->getTrans('language') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticLanguageList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <tr>
                        <td>
                            <?php if ($statisticList->getLang() == '0'): ?>
                                <?=$this->getTrans('unknown') ?>
                            <?php else: ?>
                                <?=$statisticList->getLang() ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col class="col-lg-9">
                <col class="col-lg-1">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="3"><?=$this->getTrans('osStatistic') ?></th>
                </tr>
                <tr>
                    <th colspan="2"><?=$this->getTrans('os') ?></th>
                    <th><?=$this->getTrans('numberVisits') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('statisticOSList') as $statisticList): ?>
                    <?php $progressWidth = $StatisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsTotal')); ?>
                    <tr>
                        <td>
                            <?php if ($statisticList->getOS() == '0'): ?>
                                <?=$this->getTrans('unknown') ?>
                            <?php else: ?>
                                <?=$statisticList->getOS() ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>">
                                    <?=$progressWidth ?>%
                                </div>
                            </div>
                        </td>
                        <td align="center"><?=$statisticList->getVisits() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($this->get('statisticYearMonthDayList') == '' AND $this->get('statisticYearMonthList') == ''): ?>
    <legend><?=$this->getTrans('menuStatistic') ?></legend>
    <?=$this->getTrans('noStatistic') ?>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
    $('.progress .progress-bar').progressbar({display_text: 'center'});
});
</script>
