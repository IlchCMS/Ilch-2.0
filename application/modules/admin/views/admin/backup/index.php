<h1><?=$this->getTrans('menuBackup') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <?php if ($this->get('backups') != ''): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('id') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('date') ?></th>
                    <th><?=$this->getTrans('backupFilesize') ?></th>
                    <th><?=$this->getTrans('name') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($this->get('backups') as $backup): ?>
                <tr>
                    <td><?=$this->getDeleteCheckbox('id', $backup->getId()) ?></td>
                    <td><a href="<?=$this->getUrl(['action' => 'download', 'id' => $backup->getId()], null, true) ?>" title="<?=$this->getTrans('download') ?>"><span class="fa-solid fa-download"></span></a></td>
                    <td><a href="<?=$this->getUrl(['action' => 'import', 'id' => $backup->getId()], null, true) ?>" title="<?=$this->getTrans('backupImport') ?>"><span class="fa-solid fa-database"></span></a></td>
                    <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $backup->getId()]) ?></td>
                    <td><?=$this->escape($backup->getDate()) ?></td>
                    <td><?=formatBytes(filesize(ROOT_PATH . '/backups/' . $backup->getName())) ?></td>
                    <td><?=$this->escape(preg_replace('/_[^_.]*\./', '.', $backup->getName())) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <tr>
            <td colspan="5"><?=$this->getTrans('noBackups') ?></td>
        </tr>
    <?php endif; ?>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
