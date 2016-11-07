<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('linkus') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_linkus') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('banner') ?> / <?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('linkus') as $linkus): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_linkus', $linkus->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $linkus->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $linkus->getId()]) ?></td>
                            <td>
                                <img src="<?=$this->getBaseUrl($this->escape($linkus->getBanner())) ?>" alt="<?=$this->escape($linkus->getTitle()) ?>" title="<?=$this->escape($linkus->getTitle()) ?>"></a>
                                <br />
                                &raquo; <?=$this->escape($linkus->getTitle()) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noLinkus') ?>
<?php endif; ?>
