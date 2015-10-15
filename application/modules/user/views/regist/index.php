<style>
.regist .panel-body {
    background: #eee;
}
</style>

<?php if ($this->get('regist_accept') == '1'): ?>
    <?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>
    <form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
        <?=$this->getTokenField() ?>
        <div class="regist panel panel-default">
            <div class="panel-heading">
                <?=$this->getTrans('rules') ?>
            </div>
            <div class="panel-body">
                <?=$this->get('regist_rules') ?>
            </div>
        </div>
        <label class="checkbox inline <?php if ($this->get('error') != '') { echo 'text-danger'; } ?>" style="margin-left: 20px;">
            <input type="checkbox" name="acceptRule" value="1"> <?=$this->getTrans('acceptRule') ?>
        </label>
        <div class="col-lg-12" align="right">
            <?=$this->getSaveBar('nextButton', 'Regist') ?>
        </div>
    </form>
<?php else: ?>
    <?=$this->getTrans('noRegistAccept') ?>
<?php endif; ?>
