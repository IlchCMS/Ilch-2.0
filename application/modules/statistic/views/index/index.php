<?php 
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
                <td><?=$this->getTrans('statTotal') ?>: <?=$this->get('visitsTotal') ?></td>
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
                <th colspan="2"><?=$this->getTrans('yearMonthDay') ?></th>
                <th><?=$this->getTrans('numberVisits') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->get('statisticYearMonthDayList') as $statisticList): ?>
                <?php $progressWidth = $statisticList->getVisits() / $this->get('visitsTotal') * 100; ?>
                <?php $progressWidth = round($progressWidth, 0); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><?=$date->format("Y-m-d", true) ?></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progressWidth ?>%; min-width: 2em;">
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
                <?php $progressWidth = $statisticList->getVisits() / $this->get('visitsTotal') * 100; ?>
                <?php $progressWidth = round($progressWidth, 0); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)))?>"><?=$date->format("Y - F", true) ?></a></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progressWidth ?>%; min-width: 2em;">
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
                <?php $progressWidth = $statisticList->getVisits() / $this->get('visitsTotal') * 100; ?>
                <?php $progressWidth = round($progressWidth, 0); ?>
                <?php $date = new \Ilch\Date($statisticList->getDate()); ?>
                <tr>
                    <td><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'year' => $date->format("Y", true)))?>"><?=$date->format("Y", true) ?></a></td>
                    <td>
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progressWidth ?>%; min-width: 2em;">
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
                <?php $progressWidth = $statisticList->getVisits() / $this->get('visitsTotal') * 100; ?>
                <?php $progressWidth = round($progressWidth, 0); ?>
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
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progressWidth ?>%; min-width: 2em;">
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
                <?php $progressWidth = $statisticList->getVisits() / $this->get('visitsTotal') * 100; ?>
                <?php $progressWidth = round($progressWidth, 0); ?>
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
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progressWidth ?>%; min-width: 2em;">
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
                <?php $progressWidth = $statisticList->getVisits() / $this->get('visitsTotal') * 100; ?>
                <?php $progressWidth = round($progressWidth, 0); ?>
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
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progressWidth ?>%; min-width: 2em;">
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
