<style>
.group-image{
    max-width: 100px;
    height: 50px;
    margin: -8px;
}
</style>

<h1><?=$this->getTrans('manageEnemy') ?></h1>
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
                            <td><?=$this->getDeleteCheckbox('check_enemy', $enemy->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $enemy->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $enemy->getId()]) ?></td>
                            <td><?=(empty($enemy->getEnemyHomepage())) ? $this->escape($enemy->getEnemyName()) : '<a href="'.$enemy->getEnemyHomepage().'">'.$enemy->getEnemyName().'</a>' ?></td>
                            <td><?=$this->escape($enemy->getEnemyTag()) ?></td>
                            <td><?=(empty($enemy->getEnemyImage())) ? '' : '<img class="group-image" src="'.$this->getBaseUrl($enemy->getEnemyImage()).'" />' ?></td>
                            <td><?=$this->escape($enemy->getEnemyContactName()) ?></td>
                            <td><?=$enemy->getEnemyContactEmail() ?></td>
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
