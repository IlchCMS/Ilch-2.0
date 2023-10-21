<h1><?=$this->getTrans('newPassword') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <label for="email" class="control-label col-xl-2">
            <?=$this->getTrans('email') ?>:
        </label>
        <div class="col-xl-8">
            <input type="email"
                   class="form-control"
                   id="email"
                   name="email"
                   value="<?=$this->originalInput('email') ?>" />
        </div>
    </div>
    <div class="col-xl-10" align="right">
        <?=$this->getSaveBar('buttonNewPassword', 'NewPassword') ?>
    </div>
</form>
