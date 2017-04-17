<?= ($this->get('success') == "") ? '' : (($this->get('success') == 'false') ? '<div class="alert alert-danger">'.$this->getTrans('subscribeFailed').'</div>' : '<div class="alert alert-success">'.$this->getTrans('subscribeSuccess').'</div>'); ?>
<form class="form-horizontal" action="" method="post">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       placeholder="<?=$this->getTrans('email') ?>" 
                       required />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <?=$this->getSaveBar('entry', 'NewsletterBox') ?>
        </div>
    </div>
</form>
