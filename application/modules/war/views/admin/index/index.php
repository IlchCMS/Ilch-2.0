<?php

/** @var \Ilch\View $this */

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');

/** @var \Modules\War\Mappers\Enemy $enemyMapper */
$enemyMapper = $this->get('enemyMapper');
/** @var \Modules\War\Mappers\Group $groupMapper */
$groupMapper = $this->get('groupMapper');
?>
<h1><?=$this->getTrans('manageWarOverview') ?></h1>
<?php if ($this->get('war')) : ?>
    <div class="row mb-3">
        <label class="col-lg-2 col-form-label" for="filterLastNext">
            <?=$this->getTrans('showOnly') ?>
        </label>
        <div class="col-lg-2">
            <select class="form-select" id="filterLastNext" name="filterLastNext">
                <option value="0" <?=($this->getRequest()->getParam('filterLastNext') == 0) ? 'selected=""' : '' ?>><?=$this->getTrans('all') ?></option>
                <option value="1" <?=($this->getRequest()->getParam('filterLastNext') == 1) ? 'selected=""' : '' ?>><?=$this->getTrans('warStatusOpen') ?></option>
                <option value="2" <?=($this->getRequest()->getParam('filterLastNext') == 2) ? 'selected=""' : '' ?>><?=$this->getTrans('warStatusClose') ?></option>
            </select>
        </div>
    </div>
    <?=$pagination->getHtml($this, []) ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-xl-2" />
                    <col class="col-xl-2" />
                    <col class="col-xl-2" />
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_war') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('enemyName') ?></th>
                        <th><?=$this->getTrans('groupName') ?></th>
                        <th><?=$this->getTrans('nextWarTime') ?></th>
                        <th><?=$this->getTrans('warStatus') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /** @var \Modules\War\Models\War $war */
                    foreach ($this->get('war') as $war) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_war', $war->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $war->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $war->getId()]) ?></td>
                            <td><?php
                            $enemy = $enemyMapper->getEnemyById($war->getWarEnemy());
                            echo $this->escape($enemy ? $enemy->getEnemyName() : '');
                            ?></td>
                            <td><?php
                            $group = $groupMapper->getGroupById($war->getWarGroup());
                            echo $this->escape($group ? $group->getGroupName() : '');
                            ?></td>
                            <td><?=date('d.m.Y H:i', strtotime($war->getWarTime())) ?></td>
                            <td>
                                <?php
                                if ($war->getWarStatus() == '1') {
                                    echo $this->getTrans('warStatusOpen');
                                } elseif ($war->getWarStatus() == '2') {
                                    echo $this->getTrans('warStatusClose');
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$pagination->getHtml($this, []) ?>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTranslator()->trans('noWars') ?>
<?php endif; ?>
<script>
    $(function() {
        $('#filterLastNext').change(function() {
            if ($(this).val() !== 0) {
                window.open("<?=$this->getUrl(['action' => 'index']) ?>/filterLastNext/"+$(this).val(),"_self");
            } else {
                window.open("<?=$this->getUrl(['action' => 'index']) ?>","_self");
            }
        })
    })
</script>
