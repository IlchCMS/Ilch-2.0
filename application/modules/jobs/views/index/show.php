<?php
$job = $this->get('job');
$jobs = $this->get('jobs');
?>

<legend><?=$this->getTrans('menuJobs') ?></legend>
<?php if ($job != '' AND $this->getUser()): ?>
    <form action="" class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="title" class="col-lg-3 control-label">
                <div class="text-left">
                    <?=$this->getTrans('applyAs') ?>:                
                </div>
            </label>
            <div class="col-lg-3">
                <select class="form-control" name="title">
                    <?php foreach($jobs as $jobs): ?>
                        <option value="<?=$jobs->getTitle() ?>" <?=($this->getRequest()->getParam('id') == $jobs->getId()) ? 'selected="selected"' : '' ?>>
                            <?=$this->escape($jobs->getTitle()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea class="form-control ckeditor"
                       name="text"
                       id="ck_1"
                       toolbar="ilch_html"
                       rows="5"></textarea>
            </div>
        </div>
        <div class="col-lg-12" align="right">
            <?=$this->getSaveBar('apply', 'Apply') ?>
        </div>
    </form>
<?php endif; ?>
