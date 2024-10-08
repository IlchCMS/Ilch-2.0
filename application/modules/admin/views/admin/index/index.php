<?php

/** @var \Ilch\View $this */

if ($this->getUser()->getFirstName() != '') {
    $name = $this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName();
} else {
    $name = $this->getUser()->getName();
}

$ilchNews = $this->get('ilchNews');

/** @var \Modules\Admin\Models\Notification[] $notifications */
$notifications = $this->get('notifications');
/** @var string $version */
$version = $this->get('version');

/** @var \Ilch\Accesses $accesses */
$accesses = $this->get('accesses');
?>

<h3><?=$this->getTrans('welcomeBack', $this->escape($name)) ?> !</h3>
<?=$this->getTrans('welcomeBackDescripton') ?>
<br /><br /><br />
<?php if (!empty($notifications)) : ?>
<form method="POST">
    <?=$this->getTokenField() ?>
<?php endif; ?>
<div class="row">
    <div class="col-xl-6 col-lg-6">
        <h1>
            <?=$this->getTrans('system') ?>
            <?php if ($this->get('foundNewVersions')) : ?>
                <span class="badge bg-danger"><?=$this->getTrans('notUpToDate') ?></span>
            <?php elseif ($this->get('curlErrorOccurred')) : ?>
                <span class="badge bg-warning"><?=$this->getTrans('versionQueryFailed') ?></span>
            <?php else : ?>
                <span class="badge bg-success"><?=$this->getTrans('upToDate') ?></span>
            <?php endif; ?>
        </h1>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="col-xl-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th></th>
                        <th><?=$this->getTrans('version') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$this->getTrans('installedVersion') ?></td>
                        <td><?=$version ?></td>
                    </tr>
                    <tr>
                        <td><?=$this->getTrans('newestVersion') ?></td>
                        <td>
                            <?php if ($this->get('newestVersion')) : ?>
                                <?=$this->escape($this->get('newestVersion')) ?>
                            <?php elseif ($this->get('curlErrorOccurred')) : ?>
                                <?=$this->getTrans('versionNA') ?>
                            <?php else : ?>
                                <?=$version ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($this->get('foundNewVersions')) : ?>
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
        <?php if (!empty($ilchNews)) : ?>
            <h1>ilch <?=$this->getTrans('news') ?> <i id="refreshNews" class="fa-solid fa-arrows-rotate"></i></h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-xl-2">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th><?=$this->getTrans('date') ?></th>
                            <th><?=$this->getTrans('title') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ilchNews as $news) : ?>
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
        <?php if (!empty($notifications)) : ?>
            <h1><?=$this->getTrans('notifications') ?></h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="icon_width">
                        <col class="col-xl-2">
                        <col class="col-xl-2">
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
                        <?php foreach ($notifications as $notification) : ?>
                            <?php if (($accesses && $accesses->hasAccess('Admin_' . $notification->getModule(), 'Admin_' . $notification->getModule()))) : ?>
                                <?php $date = new \Ilch\Date($notification->getTimestamp()); ?>
                            <tr>
                                <td><?=$this->getDeleteCheckbox('check_notifications', $notification->getId()) ?></td>
                                <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $notification->getId()]) ?></td>
                                <td><a href="<?=$this->getUrl(['action' => 'revokePermission', 'key' => $notification->getModule()], null, true) ?>" title="<?=$this->getTrans('revokePermission') ?>"><i class="fa-solid fa-bell-slash"></i></a></td>
                                <td><?=$date->format('d.m.Y', true) ?></td>
                                <td><a href="<?=$notification->getURL() ?>" target="_blank" rel="noopener" title="<?=$this->escape($notification->getModule()) ?>"><?=$this->escape($notification->getModule()) ?></a></td>
                                <td><?=$this->escape($notification->getMessage()) ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($notifications)) : ?>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
<?php endif; ?>

<script>
    $("#refreshNews").click(function(){
        location.href="<?=$this->getUrl(['action' => 'refreshnews', 'from' => 'index']) ?>";
    });
</script>
