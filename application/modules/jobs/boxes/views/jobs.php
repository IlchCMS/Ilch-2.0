<?php

/** @var \Ilch\View $this */

/** @var \Modules\Jobs\Models\Jobs[]|null $jobs */
$jobs = $this->get('jobs');
?>
<?php if ($jobs) : ?>
    <ul class="list-unstyled">
        <?php foreach ($jobs as $job) : ?>
            <li>
                <a href="<?=$this->getUrl('jobs/index/show/id/' . $job->getId()) ?>">
                    <?=$this->escape($job->getTitle()) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>
