<?php
$profil = $this->get('profil');
$notifications = $this->get('notifications');
$openFriendRequests = $this->get('openFriendRequests');
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('welcome') ?> <?=$this->escape($profil->getName()) ?></h1>

            <?php if (!empty($openFriendRequests)): ?>
                <h1><?=$this->getTrans('friendRequests') ?></h1>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <colgroup>
                            <col class="icon_width">
                            <col class="icon_width">
                            <col class="col-lg-12">
                        </colgroup>
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th><?=$this->getTrans('userName') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($openFriendRequests as $openFriendRequest): ?>
                            <tr>
                                <td><a href="<?=$this->getUrl(['action' => 'approveFriendRequest', 'id' => $openFriendRequest->getUserId()], null, true) ?>" title="<?=$this->getTrans('approveFriendRequest') ?>"><i class="fa fa-check text-success"></i></a></td>
                                <td><a href="<?=$this->getUrl(['action' => 'removeFriend', 'id' => $openFriendRequest->getUserId()], null, true) ?>" title="<?=$this->getTrans('declineFriendRequest') ?>"><i class="fa fa-ban text-danger"></i></a></td>
                                <td><a href="<?=$this->getUrl(['controller' => 'profil', 'action' => 'index', 'user' => $openFriendRequest->getUserId()]) ?>" title="<?=$this->escape($openFriendRequest->getName()) ?>s <?=$this->getTrans('profile') ?>"><?=$openFriendRequest->getName() ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if (!empty($notifications)): ?>
                <h1><?=$this->getTrans('notifications') ?></h1>
                <form class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <colgroup>
                                <col class="icon_width">
                                <col class="icon_width">
                                <col class="icon_width">
                                <col class="icon_width">
                                <col class="col-lg-2">
                                <col class="col-lg-2">
                                <col class="col-lg-8">
                            </colgroup>
                            <thead>
                            <tr>
                                <th><?=$this->getCheckAllCheckbox('check_notifications') ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?=$this->getTrans('notificationDate') ?></th>
                                <th><?=$this->getTrans('notificationModule') ?></th>
                                <th><?=$this->getTrans('notificationMessage') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($notifications as $notification): ?>
                                <?php $date = new \Ilch\Date($notification->getTimestamp()); ?>
                                <tr>
                                    <td><?=$this->getDeleteCheckbox('check_notifications', $notification->getId()) ?></td>
                                    <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $notification->getId()]) ?></td>
                                    <td><a href="<?=$this->getUrl(['action' => 'revokePermission', 'key' => $notification->getModule()], null, true) ?>" title="<?=$this->getTrans('revokePermissionAll') ?>"><i class="fas fa-bell-slash text-danger"></i></a></td>
                                    <td><a href="<?=$this->getUrl(['action' => 'revokePermission', 'key' => $notification->getModule(), 'type' => $notification->getType()], null, true) ?>" title="<?=$this->getTrans('revokePermissionType') ?>"><i class="far fa-bell-slash text-danger"></i></a></td>
                                    <td><?=$date->format('d.m.Y H:i:s', true) ?></td>
                                    <td><a href="<?=$notification->getURL() ?>" target="_blank" rel="noopener" title="<?=$this->escape($notification->getModule()) ?>"><?=$this->escape($notification->getModule()) ?></a></td>
                                    <td><?=$this->escape($notification->getMessage()) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?=$this->getListBar(['delete' => 'delete']) ?>
                </form>
            <?php else: ?>
                <p><?=$this->getTrans('notificationNoNotificiations') ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    var deleteSelectedEntries = <?=json_encode($this->getTrans('deleteSelectedEntries')) ?>;
</script>
