<h1><?=$this->getTrans('manage') ?></h1>
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
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('dateTime') ?></th>
                        <th><?=$this->getTrans('by') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('event') as $event): ?>
                        <?php
                        $userMapper = new \Modules\User\Mappers\User();
                        $user = $userMapper->getUserById($event->getUserId());
                        if (!$user) {
                            $user = $userMapper->getDummyUser();
                        }
                        ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $event->getId()) ?></td>
                            <td><a href="<?=$this->getURL('events/index/treat/id/'.$event->getId()) ?>" target="_blank" title="<?=$this->getTrans('edit') ?>"><i class="fa-solid fa-pen-to-square text-success"></i></a></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $event->getId()]) ?></td>
                            <td>
                                <?=date('d.m.Y H:i', strtotime($event->getStart())) ?>
                                <?php if ($event->getEnd() !== '0000-00-00 00:00:00'): ?>
                                    - <?=date('H:i', strtotime($event->getEnd())) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($event->getUserId()): ?>
                                    <a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a>
                                <?php endif; ?>
                            </td>
                            <td><a href="<?=$this->getUrl('events/show/event/id/'.$event->getId()) ?>" target="_blank"><?=$this->escape($event->getTitle()) ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noEvent') ?>
<?php endif; ?>
