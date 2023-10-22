<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('receivers') != ''): ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_receivers') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('name') ?></th>
                        <th><?=$this->getTrans('email') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('receivers') as $receiver): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_receivers', $receiver->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $receiver->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $receiver->getId()]) ?></td>
                            <td><?=$this->escape($receiver->getName()) ?></td>
                            <td><?=$this->escape($receiver->getEmail()) ?></td>
                        </tr>
                   <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noReceivers') ?>
<?php endif; ?>
