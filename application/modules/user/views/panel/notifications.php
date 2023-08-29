<?php
$notificationPermissions = $this->get('notificationPermissions');
$index = 0;
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('settingsNotifications') ?></h1>
            <?php if ($notificationPermissions) : ?>
                <h1><?=$this->getTrans('settingsNotificationPermissions') ?></h1>
                <form class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <colgroup>
                                <col class="icon_width">
                                <col class="icon_width">
                                <col class="icon_width">
                                <col class="col-lg-2">
                                <col class="col-lg-10">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><?=$this->getCheckAllCheckbox('check_notificationPermissions') ?></th>
                                    <th></th>
                                    <th></th>
                                    <th><?=$this->getTrans('notificationType') ?></th>
                                    <th><?=$this->getTrans('notificationModule') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($notificationPermissions as $notificationPermission): ?>
                                <?php
                                if ($notificationPermission->getGranted()) {
                                    $value = 'true';
                                    $translation = 'revokePermission';
                                    $icon = 'fa-solid fa-bell text-success';
                                } else {
                                    $value = 'false';
                                    $translation = 'grantPermission';
                                    $icon = 'fa-solid fa-bell-slash text-danger';
                                }
                                ?>
                                <tr>
                                    <input type="hidden" class="form-control" name="data[<?=$index ?>][key]" value="<?=$notificationPermission->getModule() ?>">
                                    <td><?=$this->getDeleteCheckbox('check_notificationPermissions', $notificationPermission->getId()) ?></td>
                                    <td><?=$this->getDeleteIcon(['action' => 'deletePermission', 'id' => $notificationPermission->getId()]) ?></td>
                                    <td><a href="<?=$this->getUrl(['action' => 'changePermission', 'id' => $notificationPermission->getId(), 'revoke' => $value, 'all' => ($notificationPermission->getType() === '') ? 'true' : 'false'], null, true) ?>" title="<?=$this->getTrans($translation) ?>"><i class="<?=$icon ?>"></i></a></td>
                                    <td><?=($notificationPermission->getType() !== '') ? $this->escape($this->getTrans($notificationPermission->getType())) : $this->getTrans('notificationsAllTypes') ?></td>
                                    <td><?=$this->escape($notificationPermission->getModule()) ?></td>
                                </tr>
                                <?php $index++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?=$this->getListBar(['delete' => 'delete']) ?>
                </form>
            <?php else: ?>
                <?=$this->getTrans('noNotificationPermissions') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    var deleteSelectedEntries = <?=json_encode($this->getTrans('deleteSelectedEntries')) ?>;
</script>
