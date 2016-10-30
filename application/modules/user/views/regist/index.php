<?php if ($this->get('regist_accept') == '1'): ?>
    <?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>

    <!-- Fehlerausgabe der Validation -->
    <?php if ($this->validation()->hasErrors()): ?>
        <div class="alert alert-danger" role="alert">
            <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
            <ul>
                <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <!-- Ende Fehlerausgabe der Validation -->

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
                <div class="pull-left checkbox inline <?=$this->validation()->hasError('acceptRule') ? 'has-error' : '' ?>">
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
