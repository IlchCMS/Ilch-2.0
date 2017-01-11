<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('teams') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
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
                        <th><?=$this->getCheckAllCheckbox('check_teams') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('menuTeam') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('teams') as $team): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_teams', $team->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $team->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $team->getId()]) ?></td>
                            <td><?=$this->escape($team->getName()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noTeams') ?>
<?php endif; ?>
