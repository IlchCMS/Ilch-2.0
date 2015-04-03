<form class="form-horizontal" action="" method="post">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-12">
            <input class="form-control"
                   id="email"
                   name="email"
                   type="text"
                   placeholder="E-Mail"
                   value="" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <button type="submit" value="1" name="form_<?= $this->get('uniqid') ?>" class="btn">
                <?=$this->getTrans('entry') ?>
            </button>
        </div>
    </div>
</form>