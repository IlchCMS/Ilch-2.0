<div class="col-12">
    <div class="row">
        <div class="login_container">
            <div class="form_container">
                <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>">
                <div class="centering text-center error-container">
                    <h2 class="without-margin">
                        <?=$this->getTrans('error') ?>
                        <span class="text-warning">
                            <big>404</big>
                        </span>
                    </h2>
                    <h4 class="text-warning"><?=$this->escape($this->get('error')) ?> "<?=$this->escape($this->get('errorText')) ?>" <?=$this->getTrans('notFound'); ?>!</h4>
                </div>
                <div class="row text-center">
                    <a class="btn btn-outline-secondary" href="<?=$this->getUrl() ?>"><?=$this->getTrans('back'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
