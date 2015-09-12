<?php if ($this->getUser() AND $this->getUser()->getId() != $this->getRequest()->getParam('user')): ?>
    <?php $userMapper = new Modules\User\Mappers\User(); ?>
    <?php $profil = $this->get('profil'); ?>

    <?php if ($profil->getOptMail() == 1): ?>
        <legend><?=$this->getTrans('mailTo') ?> <?=$profil->getName() ?></legend>
        <form method="POST" class="form-horizontal" action="">
            <?=$this->getTokenField() ?>
            <div class="form-group">
                <label for="subject" class="col-lg-2 control-label">
                    <?=$this->getTrans('subject') ?>:
                </label>
                <div class="col-lg-8">
                    <input id="subject" 
                           class="form-control" 
                           name="subject" 
                           type="text" 
                           value="" />
                </div>
            </div>
            <div class="form-group">
                <label for="message" class="col-lg-2 control-label">
                    <?=$this->getTrans('message') ?>:
                </label>
                <div class="col-lg-8">
                    <textarea class="form-control" 
                              rows="5" 
                              name="message" 
                              id="ilch_html"></textarea>
                </div>
            </div>
            <div class="col-lg-offset-2 col-lg-10">
                <?=$this->getSaveBar('profileSubmit', 'Contact') ?>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>
