<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('event') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('dateTime') ?></th>
                        <th><?=$this->getTrans('creator') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('event') as $event): ?>
                        <?php $userMapper = new \Modules\User\Mappers\User() ?>
                        <?php $user = $userMapper->getUserById($event->getUserId()) ?>
                        <tr>
                            <td><input value="<?=$event->getId() ?>" type="checkbox" name="check_entries[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $event->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $event->getId())) ?></td>
                            <td>
                                <?=date('d.m.Y H:i', strtotime($event->getStart())) ?>
                                <?php if ($event->getEnd() != '0000-00-00 00:00:00'): ?>
                                    - <?=date('H:i', strtotime($event->getEnd())) ?>
                                <?php endif; ?>
                            </td>
                            <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a></td>
                            <td><a href="<?=$this->getUrl('admin/event/index/show/id/'.$event->getId()) ?>"><?=$event->getTitle() ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noEvent') ?>
<?php endif; ?>
