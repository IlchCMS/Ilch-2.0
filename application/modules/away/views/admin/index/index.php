<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('aways') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-1">
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_aways') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('from') ?></th>
                        <th><?=$this->getTrans('when') ?></th>
                        <th><?=$this->getTrans('reason') ?></th>
                        <th><?=$this->getTrans('text') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('aways') as $away): ?>
                        <?php $userMapper = new \Modules\User\Mappers\User() ?>
                        <?php $user = $userMapper->getUserById($away->getUserId()) ?>
                        <tr>
                            <td><input value="<?=$away->getId() ?>" type="checkbox" name="check_aways[]" /></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $away->getId())) ?></td>
                            <td>
                                <?php if ($away->getStatus() == 1): ?>
                                    <a href="<?=$this->getUrl(array('action' => 'update', 'id' => $away->getId()), null, true) ?>">
                                        <span class="fa fa-check-square-o text-info"></span>
                                    </a>
                                <?php else: ?>
                                    <a href="<?=$this->getUrl(array('action' => 'update', 'id' => $away->getId()), null, true) ?>">
                                        <span class="fa fa-square-o text-info"></span>
                                    </a>                                    
                                <?php endif; ?>
                            </td>
                            <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a></td>
                            <?php $startDate = new \Ilch\Date($away->getStart()); ?>
                            <?php $endDate = new \Ilch\Date($away->getEnd()); ?>
                            <?php if ($away->getStart() >= date('Y-m-d') OR $away->getEnd() >= date('Y-m-d')): ?>
                                <td style="color: #008000;"><?=$startDate->format('d.m.Y', true) ?> - <?=$endDate->format('d.m.Y', true) ?></td>
                            <?php else: ?>
                                <td style="color: #ff0000;"><?=$startDate->format('d.m.Y', true) ?> - <?=$endDate->format('d.m.Y', true) ?></td>             
                            <?php endif; ?>
                            <td><?=$away->getReason() ?></td>
                            <td><?=$away->getText() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noAway') ?>
<?php endif; ?>
