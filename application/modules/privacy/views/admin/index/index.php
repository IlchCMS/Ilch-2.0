<legend><?=$this->getTrans('managePrivacy') ?></legend>
<?php if ($this->get('privacys') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-12">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_privacys') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('privacys') as $privacy): ?>
                        <tr>
                            <td><input value="<?=$privacy->getId() ?>" type="checkbox" name="check_privacys[]" /></td>
                            <td>
                                <?=$this->getEditIcon(array('action' => 'treat', 'id' => $privacy->getId())) ?>
                            </td>
                            <td>
                                <?=$this->getDeleteIcon(array('action' => 'del', 'id' => $privacy->getId())) ?>
                            </td>
                            <td><?=$privacy->getTitle() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
