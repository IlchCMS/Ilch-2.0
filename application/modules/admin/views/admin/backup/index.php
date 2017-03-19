<h1><?=$this->getTrans('menuBackup') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('id') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('date') ?></th>
                    <th><?=$this->getTrans('name') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->get('backups') != ''): ?>
                    <?php foreach ($this->get('backups') as $backup): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('id', $backup->getId()) ?></td>
                            <td><a href="<?=$this->getUrl(['action' => 'download', 'id' => $backup->getId()], null, true) ?>" title="<?=$this->getTrans('download') ?>"><span class="fa fa-download"></span></a></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $backup->getId()]) ?></td>
                            <td><?=$this->escape($backup->getDate()) ?></td>
                            <td><?=$this->escape($backup->getName()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5"><?=$this->getTrans('noBackups') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
