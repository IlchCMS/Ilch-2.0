<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('entries') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                        <th><?=$this->getTrans('text') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('entries') as $entry): ?>
                        <?php $getDate = new \Ilch\Date($entry->getDate()); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $entry->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $entry->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $entry->getId()]) ?></td>
                            <td><?=$getDate->format('d.m.Y', true); ?></td>
                            <td><?=$this->escape($entry->getTitle()) ?></td>
                            <td><?=$entry->getText() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noHistorys') ?>
<?php endif; ?>
