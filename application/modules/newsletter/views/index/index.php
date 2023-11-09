<h1><?=$this->getTrans('menuNewsletter') ?></h1>
<form class="form-horizontal" action="" method="post">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('email') ? 'has-error' : '' ?>">
        <div class="col-xl-4">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       placeholder="<?=$this->getTrans('email') ?>"
                       required />
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <?=$this->getSaveBar('entry', 'Newsletter') ?>
        </div>
    </div>
</form>
