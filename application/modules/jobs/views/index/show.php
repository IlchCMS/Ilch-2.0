<?php

/** @var \Ilch\View $this */

/** @var \Modules\Jobs\Models\Jobs $job */
$job = $this->get('job');
?>
<style>
.briefcase {
    padding: 8px 8px 0 8px;
    border: 1px solid #e5e5e5;
}
</style>

<h1><?=$this->getTrans('menuJob') ?></h1>

<div class="row">
    <div class="col-xl-2">
        <i class="fa-solid fa-briefcase fa-4x briefcase"></i>
    </div>
    <div class="col-xl-10" style="margin-bottom: 35px;">
        <h1><?=$this->escape($job->getTitle()) ?></h1>
        <?=$this->purify($job->getText()) ?>
    </div>
</div>

<?php if ($this->getUser()) : ?>
    <h1><?=$this->getTrans('apply') ?></h1>
    <form action="" method="POST">
        <?=$this->getTokenField() ?>
        <div class="row mb-3 <?=$this->validation()->hasError('title') ? 'has-error' : '' ?>">
            <label for="title" class="col-xl-3 control-label">
                <div class="text-start">
                    <?=$this->getTrans('applyAs') ?>:
                </div>
            </label>
            <div class="col-xl-3">
                <select class="form-select" id="title" name="title">
                    <?php foreach ($this->get('jobs') as $job) : ?>
                        <option value="<?=$job->getTitle() ?>" <?=($this->getRequest()->getParam('id') == $job->getId()) ? 'selected="selected"' : '' ?>>
                            <?=$this->escape($job->getTitle()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3 <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
            <div class="col-xl-12">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="text"
                          toolbar="ilch_html_frontend"
                          rows="5"><?=$this->originalInput('text') ?></textarea>
            </div>
        </div>
        <div class="col-xl-12 text-end">
            <?=$this->getSaveBar('apply', 'Apply') ?>
        </div>
    </form>
<?php endif; ?>
