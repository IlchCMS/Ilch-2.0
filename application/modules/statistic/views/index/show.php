<?php
$month = $this->getRequest()->getParam('month');
$year = $this->getRequest()->getParam('year');
?>

<?php if ($year != '' AND $month != ''): ?>
    <?php $date = new \Ilch\Date($this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month').'-01'); ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('F Y', true) ?></i></legend>
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
<?php elseif ($year != ''): ?>
    <?php $date = new \Ilch\Date($this->getRequest()->getParam('year').'-01-01'); ?>
    <legend><?=$this->getTrans('menuStatistic') ?>: <i><?=$date->format('Y', true) ?></i></legend>
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
<?php endif; ?>
