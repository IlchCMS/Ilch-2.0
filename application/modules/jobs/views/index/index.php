<?php if ($this->get('jobs') != ''): ?>
    <div class="row">
        <?php foreach ($this->get('jobs') as $jobs): ?>
            <div class="col-lg-1">
                <i class="fa fa-briefcase fa-4x briefcase"></i>
            </div>
            <div class="col-lg-11" style="margin-bottom: 35px;">
                <legend><?=$this->escape($jobs->getTitle()) ?></legend>
                <?=$jobs->getText() ?>
                <br />
                <?=$this->getTrans('applicationTo') ?> <a href="mailto:<?=$jobs->getEmail() ?>"><?=$jobs->getEmail() ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>

<style>
    .briefcase {
        padding: 8px 8px 0 8px;
        border: 1px solid #e5e5e5;
    }
</style>
