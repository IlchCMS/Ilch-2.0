<?php $userMapper = $this->get('userMapper'); ?>

<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('entries') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-1">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('from') ?></th>
                        <th><?=$this->getTrans('subject') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('entries') as $entry): ?>
                        <?php $user = $userMapper->getUserById($entry->getUserId()) ?>
                        <?php $date = new \Ilch\Date($entry->getDateCreated()) ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $entry->getId()]) ?></td>
                            <td><?=$date->format("d.m.Y H:i", true) ?></td>
                            <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$this->escape($user->getName()) ?></a></td>
                            <td><a href="<?=$this->getUrl('admin/newsletter/index/show/id/'.$entry->getId()) ?>"><?=$this->escape($entry->getSubject()) ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noNewsletter') ?>
<?php endif; ?>
