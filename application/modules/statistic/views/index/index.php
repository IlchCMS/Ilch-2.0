<link href="<?=$this->getStaticUrl('css/bootstrap-progressbar-3.3.4.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?=$this->getStaticUrl('js/bootstrap-progressbar.js') ?>"></script>

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
$statisticMapper = new \Modules\Statistic\Mappers\Statistic();
$languageCodes = new \Modules\Statistic\Plugins\languageCodes();
$date = new \Ilch\Date();
?>

<?php if($this->getUser()): ?>
    <?php $userMapper = new \Modules\User\Mappers\User() ?>
    <?php $userCheck = $userMapper->getUserById($this->getUser()->getId()) ?>
<?php endif; ?>

<legend><?=$this->getTrans('menuStatistic') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-3">
            <col class="col-lg-3">
            <col class="col-lg-3">
            <col class="col-lg-3">
        </colgroup>
        <thead>
            <tr>
                <th colspan="4"><?=$this->getTrans('visitsStatistic') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->getTrans('statToday') ?>: <?=$this->get('visitsToday') ?></td>
                <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$this->getTrans('statMonth') ?>: <?=$this->get('visitsMonth') ?></a></td>
                <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$this->getTrans('statYear') ?>: <?=$this->get('visitsYear') ?></a></td>
                <td><?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsAllTotal') ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2">
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><?=$date->format("H") ?>:00 <?=$this->getTrans('clock') ?></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><?=$date->format("l") ?></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsMonth')); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><?=$date->format("Y-m-d", true) ?></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsYearTotal')); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$date->format("Y - F", true) ?></a></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$date->format("Y", true) ?></a></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
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
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
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
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
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
            <col />
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
                <?php $progressWidth = $statisticMapper->getPercent($statisticList->getVisits(), $this->get('visitsAllTotal')); ?>
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
                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?=$progressWidth ?>"></div>
                        </div>
                    </td>
                    <td align="center"><?=$statisticList->getVisits() ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.progress .progress-bar').progressbar({display_text: 'center'});
});
</script>
