<legend><?php echo $this->getTrans('manageEnemy');?></legend>
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
                    <th><?php echo $this->getTrans('enemysName'); ?></th>
                    <th><?php echo $this->getTrans('enemysTag'); ?></th>
                    <th><?php echo $this->getTrans('enemysLogo'); ?></th>
                    <th><?php echo $this->getTrans('enemysContactName'); ?></th>
                    <th><?php echo $this->getTrans('enemysContactEmail'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('enemy') as $enemy) : ?>
                    <tr>
                        <td><input value="<?=$enemy->getId()?>" type="checkbox" name="check_enemy[]" /></td>
                        <td>
                            <?php echo $this->getEditIcon(array('action' => 'treat', 'id' => $enemy->getId())); ?>
                        </td>
                        <td>
                            <?php $deleteArray = array('action' => 'del', 'id' => $enemy->getId()); ?>
                            <?=$this->getDeleteIcon($deleteArray)?>
                        </td>
                        <td>
                            <?php echo $this->escape($enemy->getEnemyName()); ?>
                        </td>
                        <td>
                            <?php echo $this->escape($enemy->getEnemyTag()); ?>
                        </td>
                        <td>
                            <img class="group-image" src="<?=$this->getBaseUrl($this->escape($enemy->getEnemyLogo())); ?>" />
                        </td>
                        <td>
                            <?php echo $this->escape($enemy->getEnemyContactName()); ?>
                        </td>
                        <td>
                            <?php echo $this->escape($enemy->getEnemyContactEmail()); ?>
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
