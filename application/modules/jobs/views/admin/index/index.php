<?php

/** @var \Ilch\View $this */

/** @var \Modules\Jobs\Models\Jobs[]|null $jobs */
$jobs = $this->get('jobs');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($jobs) : ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('title') ?></th>
                        <th><?=$this->getTrans('email') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $job->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $job->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $job->getId()]) ?></td>
                            <td>
                                <a href="<?=$this->getUrl(['action' => 'update', 'id' => $job->getId()], null, true) ?>">
                                    <span class="fa-regular fa-square<?=$job->getShow() ? '-check' : '' ?> text-info" title="<?=$this->getTrans($job->getShow()  ? 'hide' : 'show') ?>"></span>
                                </a>
                            </td>
                            <td><?=$this->escape($job->getTitle()) ?></td>
                            <td><?=$this->escape($job->getEmail()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>
