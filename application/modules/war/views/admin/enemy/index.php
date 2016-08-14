<style>
.group-image{
    max-width: 100px;
    height: 50px;
    margin: -8px;
}
</style>

<legend><?=$this->getTrans('manageEnemy') ?></legend>
<?php if ($this->get('enemy') != ''): ?>
    <?=$this->get('pagination')->getHtml($this, []) ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
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
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_Enemy') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('enemysName') ?></th>
                        <th><?=$this->getTrans('enemysTag') ?></th>
                        <th><?=$this->getTrans('enemysImage') ?></th>
                        <th><?=$this->getTrans('enemysContactName') ?></th>
                        <th><?=$this->getTrans('enemysContactEmail') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('enemy') as $enemy): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="check_enemy[]" value="<?=$enemy->getId() ?>" />
                            </td>
                            <td>
                                <?=$this->getEditIcon(['action' => 'treat', 'id' => $enemy->getId()]) ?>
                            </td>
                            <td>
                                <?php $deleteArray = ['action' => 'del', 'id' => $enemy->getId()] ?>
                                <?=$this->getDeleteIcon($deleteArray) ?>
                            </td>
                            <td>
                                <?=$enemy->getEnemyName() ?>
                            </td>
                            <td>
                                <?=$enemy->getEnemyTag() ?>
                            </td>
                            <td>
                                <img class="group-image" src="<?=$this->getBaseUrl($enemy->getEnemyImage()) ?>" />
                            </td>
                            <td>
                                <?=$enemy->getEnemyContactName() ?>
                            </td>
                            <td>
                                <?=$enemy->getEnemyContactEmail() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->get('pagination')->getHtml($this, []) ?>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTranslator()->trans('noEnemy') ?>
<?php endif; ?>
