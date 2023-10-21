<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-xl-2">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('providerName') ?></th>
                    <th><?=$this->getTrans('providerModule') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('providers') as $provider): ?>
                    <tr>
                        <td><?=$this->getEditIcon(['action' => 'edit', 'key' => $provider->getKey()]) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'delete', 'key' => $provider->getKey()]) ?></td>
                        <td><i class="fa-solid fa-fw <?= $provider->getIcon() ?>"></i> <?=$this->escape($provider->getName()) ?></td>
                        <td>
                            <?php if (empty($provider->getModule())): ?>
                                <span class="text-danger">
                                    <i class="fa-solid fa-xmark fa-fw"></i> <?= $this->getTrans('providersNoModuleSelectedOrInstalled') ?>
                                </span>
                            <?php else: ?>
                                <span class="text-success">
                                    <i class="fa-solid fa-check fa-fw"></i> <b><?=$this->escape($provider->getModuleName()) ?></b> (<?= $provider->getModule() ?>)
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
