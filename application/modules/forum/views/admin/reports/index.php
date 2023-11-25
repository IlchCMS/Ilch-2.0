<?php

/** @var \Ilch\View $this */

use Ilch\Date;

$reasonTransKeys = [
    '1' => 'illegalContent',
    '2' => 'spam',
    '3' => 'wrongTopic',
    '4' => 'other',
]
?>
<h1><?=$this->getTrans('reports') ?></h1>
<?php if (!empty($this->get('reports'))) : ?>
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
                    <?php
                    /** @var \Modules\Forum\Models\Report $report */
                    foreach ($this->get('reports') as $report) : ?>
                        <?php $date = new Date($report->getDate()); ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_forumReports', $report->getId()) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $report->getId()]) ?></td>
                            <td><?=$date->format('d.m.y - H:i', true) ?></td>
                            <td><?=$this->getTrans($reasonTransKeys[$report->getReason()]) ?></td>
                            <td><a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'reports', 'action' => 'show', 'id' => $report->getId()], 'admin') ?>"><?=$this->getTrans('showDetails') ?></a></td>
                            <?php if ($report->getTopicId()) : ?>
                            <td><a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $report->getTopicId() . '#' . $report->getPostId()], '') ?>" target="_blank"><?=$this->getTrans('showPost') ?></a></td>
                            <?php else : ?>
                            <td><?=$this->getTrans('reportPostNotExisting') ?></td>
                            <?php endif; ?>
                            <td><?=$this->escape($report->getUsername()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <tr>
        <td colspan="5"><?=$this->getTrans('noReports') ?></td>
    </tr>
<?php endif; ?>
