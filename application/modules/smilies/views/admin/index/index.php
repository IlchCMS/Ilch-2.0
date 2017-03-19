<h1><?= $this->getTrans('manage') ?></h1>
<?php if ($this->get('smilies') != ''): ?>
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
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('name') ?></th>
                        <th><?=$this->getTrans('image') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('smilies') as $smilies): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $smilies->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $smilies->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $smilies->getId()]) ?></td>
                            <td><?=$this->escape($smilies->getName()) ?></td>
                            <td><img src="<?=$this->getBaseUrl($this->escape($smilies->getUrl())) ?>" title="<?=$this->escape($smilies->getName()) ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noSmilies') ?>
<?php endif; ?>
