<h1><?=$this->getTrans('ranks') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width" />
                <col class="icon_width" />
                <col class="icon_width" />
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_forumRanks') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('title') ?></th>
                    <th><?=$this->getTrans('posts') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($this->get('ranks'))): ?>
                    <?php foreach ($this->get('ranks') as $rank): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_forumRanks', $rank->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $rank->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $rank->getId()]) ?></td>
                            <td><?=$this->escape($rank->getTitle()) ?></td>
                            <td><?=$rank->getPosts() ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5"><?=$this->getTrans('noRanks') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getListBar(['delete' => 'delete']) ?>
</form>
