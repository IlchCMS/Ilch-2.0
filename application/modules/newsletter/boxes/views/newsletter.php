<form class="form-horizontal" action="" method="post">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                <input class="form-control"
                       id="email"
                       name="email"
                       type="text"
                       placeholder="<?=$this->getTrans('email') ?>"
                       value="" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <button type="submit" value="1" name="form_<?=$this->get('uniqid') ?>" class="btn">
                <?=$this->getTrans('entry') ?>
            </button>
        </div>
    </div>
</form>