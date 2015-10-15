<legend><?=$this->getTrans('menuNewsletter') ?></legend>
<form class="form-horizontal" action="" method="post">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                <input class="form-control"
                       id="email"
                       name="email"
                       type="text"
                       placeholder="<?=$this->getTrans('email') ?>" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <?=$this->getSaveBar('entry', 'Newsletter') ?>
        </div>
    </div>
</form>
