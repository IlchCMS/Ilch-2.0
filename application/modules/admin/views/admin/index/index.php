<?php
if ($this->getUser()->getFirstName() != '') {
    $name = $this->getUser()->getFirstName().' '.$this->getUser()->getLastName();
} else {
    $name = $this->getUser()->getName();
}

$ilchNewsList = url_get_contents($this->get('ilchNewsList'), true, false, 120);
$ilchNews = json_decode($ilchNewsList);
$notifications = $this->get('notifications');
$version = $this->get('version');
?>

<h3><?=$this->getTrans('welcomeBack', $this->escape($name)) ?> !</h3>
<?=$this->getTrans('welcomeBackDescripton') ?>
<br /><br /><br />
<?php if (!empty($notifications)): ?>
<form class="form-horizontal" method="POST">
<?=$this->getTokenField() ?>
<?php endif; ?>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <h1>
            <?=$this->getTrans('system') ?>
            <?php if ($this->get('foundNewVersions')): ?>
                <span class="label label-danger"><?=$this->getTrans('notUpToDate') ?></span>
            <?php elseif ($this->get('curlErrorOccured')): ?>
                <span class="label label-warning"><?=$this->getTrans('versionQueryFailed') ?></span>
            <?php else: ?>
                <span class="label label-success"><?=$this->getTrans('upToDate') ?></span>
            <?php endif; ?>
        </h1>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="col-lg-2">
                    <col />
                </colgroup>
                <thead>
                    <th></th>
                    <th><?=$this->getTrans('version') ?></th>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$this->getTrans('installedVersion') ?></td>
                        <td><?=$version ?></td>
                    </tr>
                    <tr>
                        <td><?=$this->getTrans('serverVersion') ?></td>
                        <td>
                            <?php if ($this->get('newVersion')): ?>
                                <?=$this->get('newVersion') ?>
                            <?php elseif ($this->get('curlErrorOccured')): ?>
                                <?=$this->getTrans('versionNA') ?>
                            <?php else: ?>
                                <?=$version ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($this->get('foundNewVersions')): ?>
                        <tr>
                            <td></td>
                            <td>
                                <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'update']) ?>"><?=$this->getTrans('updateNow') ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($ilchNews)): ?>
            <h1>ilch <?=$this->getTrans('news') ?></h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-lg-2">
                        <col>
                    </colgroup>
                    <thead>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($ilchNews as $news): ?>
                            <?php $date = new \Ilch\Date($news->date); ?>
                            <tr>
                                <td><?=$date->format('d.m.Y', true) ?></td>
                                <td><a href="<?=$news->link ?>" target="_blank" rel="noopener" title="<?=$this->escape($news->title) ?>"><?=$this->escape($news->title) ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php if (!empty($notifications)): ?>
            <h1><?=$this->getTrans('notifications') ?></h1>
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
                            <th><?=$this->getCheckAllCheckbox('check_notifications') ?></th>
                            <th></th>
                            <th></th>
                            <th><?=$this->getTrans('date') ?></th>
                            <th><?=$this->getTrans('module') ?></th>
                            <th><?=$this->getTrans('message') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notifications as $notification): ?>
                            <?php $date = new \Ilch\Date($notification->getTimestamp()); ?>
                            <tr>
                                <td><?=$this->getDeleteCheckbox('check_notifications', $notification->getId()) ?></td>
                                <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $notification->getId()]) ?></td>
                                <td><a href="<?=$this->getUrl(['action' => 'revokePermission', 'key' => $notification->getModule()], null, true) ?>" title="<?=$this->getTrans('revokePermission') ?>"><i class="fa-solid fa-check text-success"></i></a></td>
                                <td><?=$date->format('d.m.Y', true) ?></td>
                                <td><a href="<?=$notification->getURL() ?>" target="_blank" rel="noopener" title="<?=$this->escape($notification->getModule()) ?>"><?=$this->escape($notification->getModule()) ?></a></td>
                                <td><?=$this->escape($notification->getMessage()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($notifications)): ?>
<?=$this->getListBar(['delete' => 'delete']) ?>
</form>
<?php endif; ?>
