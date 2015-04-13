<legend><?=$this->getTrans('manageRule') ?></legend>
<?php if ($this->get('entries') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-lg-2" />
                    <col />
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
                    <?php foreach ($this->get('entries') as $entry): ?>
                        <tr>
                            <td><input value="<?=$entry->getId() ?>" type="checkbox" name="check_entries[]" /></td>
                            <td>
                                <?=$this->getEditIcon(array('action' => 'treat', 'id' => $entry->getId())) ?>
                            </td>
                            <td>
                                <?php $deleteArray = array('action' => 'del', 'id' => $entry->getId()); ?>
                                <?=$this->getDeleteIcon($deleteArray) ?>
                            </td>
                            <td>
                                <?=$this->escape($entry->getParagraph()) ?>
                            </td>
                            <td>
                                <?=$this->escape($entry->getTitle()) ?>
                            </td>
                            <td>
                                <?=$entry->getText() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php $actions = array('delete' => 'delete') ?>
        <?=$this->getListBar($actions) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
