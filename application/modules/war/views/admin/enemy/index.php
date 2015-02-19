<legend><?=$this->getTrans('manageEnemy');?></legend>
<?php
if ($this->get('enemy') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col class="col-lg-2">
                <col class="col-lg-1">
                <col class="col-lg-2">
                <col class="col-lg-2">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_Enemy')?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('enemysName'); ?></th>
                    <th><?=$this->getTrans('enemysTag'); ?></th>
                    <th><?=$this->getTrans('enemysLogo'); ?></th>
                    <th><?=$this->getTrans('enemysContactName'); ?></th>
                    <th><?=$this->getTrans('enemysContactEmail'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('enemy') as $enemy) : ?>
                    <tr>
                        <td><input value="<?=$enemy->getId()?>" type="checkbox" name="check_enemy[]" /></td>
                        <td>
                            <?=$this->getEditIcon(array('action' => 'treat', 'id' => $enemy->getId())); ?>
                        </td>
                        <td>
                            <?php $deleteArray = array('action' => 'del', 'id' => $enemy->getId()); ?>
                            <?=$this->getDeleteIcon($deleteArray)?>
                        </td>
                        <td>
                            <?=$this->escape($enemy->getEnemyName()); ?>
                        </td>
                        <td>
                            <?=$this->escape($enemy->getEnemyTag()); ?>
                        </td>
                        <td>
                            <img class="group-image" src="<?=$this->getBaseUrl($this->escape($enemy->getEnemyLogo())); ?>" />
                        </td>
                        <td>
                            <?=$this->escape($enemy->getEnemyContactName()); ?>
                        </td>
                        <td>
                            <?=$this->escape($enemy->getEnemyContactEmail()); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    $actions = array('delete' => 'delete');

    echo $this->getListBar($actions);
    ?>
</form>
<?php
} else {
    echo $this->getTranslator()->trans('noEnemy');
}
?>
<style>
    .group-image{
        max-width: 100px;
        height: 50px;
        margin: -8px;
    }
</style>
