<?php if ($this->get('regist_accept') == '1'): ?>
    <?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>

    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=$this->getTrans('rules') ?>
            </div>
            <div class="panel-body" style="background: #eee;">
                <?=$this->get('regist_rules') ?>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left checkbox inline <?php if ($this->get('error') != '') { echo 'text-danger'; } ?>">
                    <input type="checkbox" style="margin-left: 0px;" id="acceptRule" name="acceptRule"value="1"> <label for="acceptRule"><?=$this->getTrans('acceptRule') ?></label>
                </div>
                <div class="pull-right">
                    <?=$this->getSaveBar('nextButton', 'Regist') ?>
                </div>
            </div>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noRegistAccept') ?>
<?php endif; ?>
