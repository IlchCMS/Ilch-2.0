<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('shoutbox') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-lg-2" />
                    <col class="col-lg-2" />
                    <col />
                </colgroup>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                    <th></th>
                    <th><?=$this->getTrans('from') ?></th>
                    <th><?=$this->getTrans('date') ?></th>
                    <th><?=$this->getTrans('message') ?></th>
                </tr>
                <?php foreach ($this->get('shoutbox') as $shoutbox): ?>
                    <?php $userMapper = new \Modules\User\Mappers\User() ?>
                    <?php $user = $userMapper->getUserById($shoutbox->getUid()) ?>
                    <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
                    <tr>
                        <td><input value="<?=$shoutbox->getId() ?>" type="checkbox" name="check_entries[]" /> </td>
                        <td><?=$this->getDeleteIcon(array('action' => 'delete', 'id' => $shoutbox->getId())) ?></td>
                        <?php if ($shoutbox->getUid() == '0'): ?>
                            <td><?=$this->escape($shoutbox->getName()) ?></td>
                        <?php else: ?>
                            <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a></td>
                        <?php endif; ?>
                        <td><?=$date->format("d.m.Y H:i", true) ?></td>
                        <td><?=$this->escape($shoutbox->getTextarea()) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noEntrys') ?>
<?php endif; ?>
