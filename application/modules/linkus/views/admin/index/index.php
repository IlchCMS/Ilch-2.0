<?php
$linkus = $this->get('linkus');
?>

<legend><?=$this->getTrans('manage') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?php if ($linkus != ''): ?>
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col />
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
                    <?php foreach ($linkus as $linkus): ?>
                        <tr>
                            <td><input value="<?=$linkus->getId() ?>" type="checkbox" name="check_linkus[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $linkus->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $linkus->getId())) ?></td>
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
    <?php else: ?>
        <?=$this->getTrans('noLinkus') ?>
    <?php endif; ?>
    <?=$this->getListBar(array('delete' => 'delete')) ?>
</form>
