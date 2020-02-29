<h1><?=$this->getTrans('manage') ?></h1>
<?php if (!empty($this->get('layouts'))): ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                    <col />
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_layouts') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('name') ?></th>
                    <th><?=$this->getTrans('author') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->get('layouts') as $layout): ?>
                    <tr>
                        <td><?=$this->getDeleteCheckbox('check_layouts', $layout->getKey()) ?></td>
                        <td><?=$this->getDeleteIcon(['action' => 'deleteAdvSettings', 'layoutKey' => $layout->getKey()]) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'advSettingsShow', 'layoutKey' => $layout->getKey()]) ?></td>
                        <td><a href="<?=$this->getUrl(['action' => 'advSettingsShow', 'layoutKey' => $layout->getKey()]) ?>"><?=$this->escape($layout->getName()) ?></a></td>
                        <td><?=$this->escape($layout->getAuthor()) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noLayouts') ?>
<?php endif; ?>
