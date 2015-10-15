<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('calendar') != ''): ?>
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
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('start') ?></th>
                        <th><?=$this->getTrans('end') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('calendar') as $calendar): ?>
                        <tr>
                            <td><input value="<?=$calendar->getId() ?>" type="checkbox" name="check_entries[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $calendar->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $calendar->getId())) ?></td>
                            <td><?=date('d.m.Y H:i', strtotime($calendar->getStart())) ?></td>
                            <td>
                                <?php if ($calendar->getEnd() != '0000-00-00 00:00:00'): ?>
                                    <?=date('d.m.Y H:i', strtotime($calendar->getEnd())) ?>
                                <?php endif; ?>
                            </td>
                            <td><?=$calendar->getTitle() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noCalendar') ?>
<?php endif; ?>
