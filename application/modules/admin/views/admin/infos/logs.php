<?php
$logsMapper = $this->get('logsMapper');
$userMapper = $this->get('userMapper');
?>

<legend><?=$this->getTrans('logs') ?></legend>
<?php if ($this->get('logsDate') != ''): ?>
    <?php foreach ($this->get('logsDate') as $logDate): ?>
        <?php $date = new \Ilch\Date($logDate->getDate()); ?>
        <h4><?=$date->format("d.m.Y"); ?></h4>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="col-lg-1" />
                    <col class="col-lg-2" />
                    <col />
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getTrans('time') ?></th>
                    <th><?=$this->getTrans('users') ?></th>
                    <th><?=$this->getTrans('info') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $logs = $logsMapper->getLogs($logDate->getDate()); ?>
                <?php foreach ($logs as $log): ?>
                    <?php $time = new \Ilch\Date($log->getDate()); ?>
                    <?php $user = $userMapper->getUserById($log->getUserId()) ?>
                    <tr>
                        <td><?=$time->format("H:i:s"); ?></td>
                        <td>
                            <?php
                            if ($user != '') {
                                echo $this->escape($user->getName());
                            } else {
                                echo $this->getTrans('unknown');
                            }
                            ?>
                        </td>
                        <td><?=$log->getInfo() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="content_savebox">
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <button type="submit" name="clearLog" class="btn btn-default"><?=$this->getTrans('clearLog') ?></button>
    </form>
</div>
