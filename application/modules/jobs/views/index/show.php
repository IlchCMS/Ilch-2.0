<?php
$job = $this->get('job');
?>

<style>
.briefcase {
    padding: 8px 8px 0 8px;
    border: 1px solid #e5e5e5;
}
</style>

<h1><?=$this->getTrans('menuJob') ?></h1>

<?php if ($job != ''): ?>
    <div class="row">
        <div class="col-lg-2">
            <i class="fa-solid fa-briefcase fa-4x briefcase"></i>
        </div>
        <div class="col-lg-10" style="margin-bottom: 35px;">
            <h1><?=$this->escape($job->getTitle()) ?></h1>
            <?=$this->purify($job->getText()) ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->getUser()): ?>
    <h1><?=$this->getTrans('apply') ?></h1>
    <form action="" class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="form-group <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
            <label for="title" class="col-lg-3 control-label">
                <div class="text-left">
                    <?=$this->getTrans('applyAs') ?>:
                </div>
            </label>
            <div class="col-lg-3">
                <select class="form-control" id="title" name="title">
                    <?php foreach ($this->get('jobs') as $job): ?>
                        <option value="<?=$job->getTitle() ?>" <?=($this->getRequest()->getParam('id') == $job->getId()) ? 'selected="selected"' : '' ?>>
                            <?=$this->escape($job->getTitle()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_html_frontend"
                          rows="5"></textarea>
            </div>
        </div>
        <div class="col-lg-12 text-right">
            <?=$this->getSaveBar('apply', 'Apply') ?>
        </div>
    </form>
<?php endif; ?>
