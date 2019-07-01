<?php
$reaonTransKeys = [
    '1' => 'illegalContent',
    '2' => 'spam',
    '3' => 'wrongTopic',
    '4' => 'other',
]
?>
<h1><?=$this->getTrans('reports') ?></h1>
<?php if (!empty($this->get('reports'))): ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_forumReports') ?></th>
                        <th></th>
                        <th><?=$this->getTrans('date') ?></th>
                        <th><?=$this->getTrans('reason') ?></th>
                        <th><?=$this->getTrans('detail') ?></th>
                        <th><?=$this->getTrans('post') ?></th>
                        <th><?=$this->getTrans('reporter') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('reports') as $report): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_forumReports', $report->getId()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $report->getId()]) ?></td>
                            <td><?=$this->escape($report->getDate()) ?></td>
                            <td><?=$this->getTrans($reaonTransKeys[$report->getReason()]) ?></td>
                            <td><a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'reports', 'action' => 'show', 'id' => $report->getId()], 'admin') ?>"><?=$this->getTrans('showDetails') ?></a></td>
                            <td><a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $report->getTopicId().'#'.$report->getPostId()], '') ?>" target="_blank"><?=$this->getTrans('showPost') ?></a></td>
                            <td><?=$this->escape($report->getUsername()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <tr>
        <td colspan="5"><?=$this->getTrans('noReports') ?></td>
    </tr>
<?php endif; ?>
