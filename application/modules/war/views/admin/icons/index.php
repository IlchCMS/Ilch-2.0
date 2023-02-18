<h1><?=$this->getTrans('menuGameIcons') ?></h1>
<?php if ($this->get('icons')): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_icons') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('nextWarGame') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('icons') as $game): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_icons', $game) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'key' => $game]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'key' => $game]) ?></td>
                            <td><?=$this->escape($game) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noMaps') ?>
<?php endif; ?>
