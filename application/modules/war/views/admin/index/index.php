<h1><?=$this->getTrans('manageWarOverview') ?></h1>
<?php if ($this->get('war')): ?>
    <div id="filter-media">
        <div id="filter-panel" class="collapse filter-panel">
            <div class="form-group">
                <label class="col-lg-2 control-label" for="pref-perpage">
                    <?=$this->getTrans('showOnly') ?>
                </label>
                <div class="col-lg-2">
                    <select class="form-control" id="pref-perpage" name="filterLastNext">
                        <option value="0" <?=($this->getRequest()->getParam('filterLastNext') == 0)?'selected=""':'' ?>><?=$this->getTrans('all') ?></option>
                        <option value="1" <?=($this->getRequest()->getParam('filterLastNext') == 1)?'selected=""':'' ?>><?=$this->getTrans('warStatusOpen') ?></option>
                        <option value="2" <?=($this->getRequest()->getParam('filterLastNext') == 2)?'selected=""':'' ?>><?=$this->getTrans('warStatusClose') ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?=$this->get('pagination')->getHtml($this, []) ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="col-lg-2" />
                    <col class="col-lg-2" />
                    <col class="col-lg-2" />
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
                    <?php foreach ($this->get('war') as $war): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_war', $war->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $war->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $war->getId()]) ?></td>
                            <td><?php
                            $enemy = $this->get('enemyMapper')->getEnemyById($war->getWarEnemy());
                            echo $this->escape($enemy ? $enemy->getEnemyName() : '');
                            ?></td>
                            <td><?php
                            $group = $this->get('groupMapper')->getGroupById($war->getWarGroup());
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
        <?=$this->get('pagination')->getHtml($this, []) ?>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
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
