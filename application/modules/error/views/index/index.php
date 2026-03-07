<?php

/** @var \Ilch\View $this */
?>
<div class="col-12">
    <div class="row mb-3">
        <div class="login_container">
            <div class="form_container">
                <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png') ?>" alt="ilch">
                <div class="centering text-center error-container">
                    <h2 class="without-margin">
                        <?=$this->getTrans('error') ?>
                        <span class="text-warning">
                            <span style="font-size: larger;"><?=$this->escape($this->get('errorCode')) ?></span>
                        </span>
                    </h2>
                    <h4 class="text-warning"><?=$this->escape($this->get('error')) ?> "<?=$this->escape($this->get('errorText')) ?>" <?=$this->escape($this->get('errorState')); ?>!</h4>
                </div>
                <div class="col-md-auto text-center">
                    <a class="btn btn-outline-secondary" href="<?=$this->getUrl() ?>"><?=$this->getTrans('back'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
