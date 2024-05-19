<?php

/** @var \Ilch\View $this */

/** @var \Modules\Jobs\Models\Jobs[]|null $jobs */
$jobs = $this->get('jobs');
?>
<style>
.briefcase {
    padding: 8px 8px 0 8px;
    border: 1px solid #e5e5e5;
}
</style>

<h1><?=$this->getTrans('menuJobs') ?></h1>
<?php if ($jobs) : ?>
    <div class="row">
        <?php foreach ($jobs as $job) : ?>
            <div class="col-xl-2">
                <i class="fa-solid fa-briefcase fa-4x briefcase"></i>
            </div>
            <div class="col-xl-10" style="margin-bottom: 35px;">
                <h1>
                    <a href="<?=$this->getUrl('jobs/index/show/id/' . $job->getId()) ?>">
                        <?=$this->escape($job->getTitle()) ?>
                    </a>
                </h1>
                <?=$this->purify($job->getText()) ?>

                <?php if ($this->getUser()) : ?>
                    <br />
                    <a href="<?=$this->getUrl('jobs/index/show/id/' . $job->getId()) ?>" class="btn btn-primary" role="button"><?=$this->getTrans('apply') ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>
