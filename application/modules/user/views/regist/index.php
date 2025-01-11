<?php if ($this->get('regist_accept') == '1'): ?>
    <?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>
    <form method="POST">
        <?=$this->getTokenField() ?>
        <div class="card panel-default">
            <div class="card-header">
                <?=$this->getTrans('rules') ?>
            </div>
            <div class="card-body ck-content" style="background: #eee;">
                <?=$this->get('regist_rules') ?>
            </div>
            <div class="card-footer clearfix">
                <div class="float-start checkbox inline<?=$this->validation()->hasError('acceptRule') ? ' has-error' : '' ?>">
                    <input type="checkbox" style="margin-left: 0;" id="acceptRule" name="acceptRule" value="1"> <label for="acceptRule"><?=$this->getTrans('acceptRule') ?></label>
                </div>
                <div class="float-end">
                    <?=$this->getSaveBar('nextButton', 'Regist') ?>
                </div>
            </div>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noRegistAccept') ?>
<?php endif; ?>
