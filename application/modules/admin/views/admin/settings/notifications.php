<?php
    $notificationPermissions = $this->get('notificationPermissions');
    $index = 0;
?>

<legend>
    <?=$this->getTrans('notifications') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
    </a>
</legend>
<?php if ($this->validation()->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($notificationPermissions) : ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                    <col class="col-lg-1">
                </colgroup>
                <thead>
                    <th><?=$this->getCheckAllCheckbox('check_notificationPermissions') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('module') ?></th>
                    <th><?=$this->getTrans('limit') ?></th>
                </thead>
                <tbody>
                    <?php foreach ($notificationPermissions as $notificationPermission): ?>
                        <?php
                        if ($notificationPermission->getGranted()) {
                            $test = 'true';
                            $translation = 'revokePermission';
                            $icon = 'fa fa-check text-success';
                        } else {
                            $test = 'false';
                            $translation = 'grantPermission';
                            $icon = 'fa fa-square-o';
                        }
                        ?>
                        <tr>
                            <input type="hidden" class="form-control" name="data[<?=$index ?>][key]" value="<?=$notificationPermission->getModule() ?>">
                            <td><?=$this->getDeleteCheckbox('check_notificationPermissions', $notificationPermission->getModule()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'deletePermission', 'key' => $notificationPermission->getModule()]) ?></td>
                            <td><a href="<?=$this->getUrl(['action' => 'changePermission', 'key' => $notificationPermission->getModule(), 'revoke' => $test], null, true) ?>" title="<?=$this->getTrans($translation) ?>"><i class="<?=$icon ?>"></i></a></td>
                            <td><?=$this->escape($notificationPermission->getModule()) ?></td>
                            <td class="<?=$this->validation()->hasError('limit') ? 'has-error' : '' ?>"><input type="number" class="form-control" name="data[<?=$index ?>][limit]" min="0" max="5" value="<?=$notificationPermission->getLimit() ?>"></td>
                        </tr>
                    <?php $index++; endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="content_savebox">
            <button type="submit" class="btn btn-default" name="save" value="save">
                <?=$this->getTrans('saveButton') ?>
            </button>
            <input type="hidden" class="content_savebox_hidden" name="action" value="" />
            <div class="btn-group dropup">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?=$this->getTrans('selected') ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu listChooser" role="menu">
                    <li><a href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
                </ul>
            </div>
        </div>
    </form>
<?php else : ?>
    <?=$this->getTrans('noNotificationPermissions') ?>
<?php endif; ?>
<?=$this->getDialog("infoModal", $this->getTrans('info'), $this->getTrans('notificationsInfoText')); ?>
