<legend><?=$this->getTrans('newPassword'); ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="name" class="control-label col-lg-2">
            <?=$this->getTrans('nameEmail') ?>:
        </label>
        <div class="col-lg-8">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="" />
        </div>
    </div>
    <div class="col-lg-10" align="right">
        <?=$this->getSaveBar('buttonNewPassword', 'NewPassword') ?>
    </div>
</form>
