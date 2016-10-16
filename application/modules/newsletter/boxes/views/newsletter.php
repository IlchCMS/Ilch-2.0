<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" action="" method="post">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('email', $this->get('errorFields')) ? 'has-error' : '' ?>">
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
