<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('rules') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th>§</th>
                        <th><?=$this->getTrans('title') ?></th>
                        <th><?=$this->getTrans('text');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('rules') as $rule): ?>
                        <tr>
                            <td><input type="checkbox" name="check_entries[]" value="<?=$rule->getId() ?>" /></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $rule->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $rule->getId()]) ?></td>
                            <td><?=$this->escape($rule->getParagraph()) ?></td>
                            <td><?=$this->escape($rule->getTitle()) ?></td>
                            <td><?=$rule->getText() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> 
       <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
