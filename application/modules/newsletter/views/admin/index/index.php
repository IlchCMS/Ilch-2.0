<?php
$userMapper = new \Modules\User\Mappers\User()
?>

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
                    <col class="col-lg-9">
                    <col />
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
                            <td><input value="<?= $entry->getId() ?>" type="checkbox" name="check_entries[]" /></td>
                            <td>
                                <?php $deleteArray = array('action' => 'del', 'id' => $entry->getId()) ?>
                                <?=$this->getDeleteIcon($deleteArray) ?>
                            </td>
                            <td><?=$date->format("d.m.Y H:i", true) ?></td>
                            <td><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>" target="_blank"><?=$user->getName() ?></a></td>
                            <td><a href="<?=$this->getUrl('admin/newsletter/index/show/id/'.$entry->getId()) ?>"><?=$entry->getSubject() ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noNewsletter') ?>
<?php endif; ?>
