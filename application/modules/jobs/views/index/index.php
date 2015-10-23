<style>
.briefcase {
    padding: 8px 8px 0 8px;
    border: 1px solid #e5e5e5;
}
</style>

<legend><?=$this->getTrans('menuJobs') ?></legend>
<?php if ($this->get('jobs') != ''): ?>
    <div class="row">
        <?php foreach ($this->get('jobs') as $jobs): ?>
            <div class="col-lg-1">
                <i class="fa fa-briefcase fa-4x briefcase"></i>
            </div>
            <div class="col-lg-11" style="margin-bottom: 35px;">
                <legend>
                    <a href="<?=$this->getUrl('jobs/index/show/id/' . $jobs->getId()) ?>">
                        <?=$this->escape($jobs->getTitle()) ?>
                    </a>
                </legend>
                <?=$jobs->getText() ?>

                <?php if ($this->getUser()): ?>
                    <br />
                    <a href="<?=$this->getUrl('jobs/index/show/id/' . $jobs->getId()) ?>" class="btn btn-primary" role="button"><?=$this->getTrans('apply') ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noJobs') ?>
<?php endif; ?>
