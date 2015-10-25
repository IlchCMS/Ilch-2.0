<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('awards') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField()?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-1">
                    <col class="col-lg-1">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('rank') ?></th>
                        <th><?=$this->getTrans('teamUser') ?></th>
                        <th><?=$this->getTrans('event') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('awards') as $awards) : ?>
                    <?php $getDate = new \Ilch\Date($awards->getDate()); ?>
                        <tr>
                            <td><input value="<?=$awards->getId() ?>" type="checkbox" name="check_entries[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $awards->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $awards->getId())) ?></td>
                            <td><?=$getDate->format('d.m.Y', true) ?></td>
                            <td><?=$this->escape($awards->getRank()) ?></td>
                            <?php if ($awards->getTyp() == 2): ?>
                                <td><?=$awards->getUTId() ?></td>
                            <?php else: ?>
                                <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                                <?php $user = $userMapper->getUserById($awards->getUTId()); ?>
                                <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></td>
                            <?php endif; ?>
                            <td><a href="<?=$awards->getURL() ?>" title="<?=$awards->getEvent() ?>" target="_blank"><?=$awards->getEvent() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noAwards') ?>
<?php endif; ?>