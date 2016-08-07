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
                    <input type="text"
                           class="form-control"
                           id="subject"
                           name="subject"
                           value="" />
                </div>
            </div>
            <div class="form-group">
                <label for="message" class="col-lg-2 control-label">
                    <?=$this->getTrans('message') ?>:
                </label>
                <div class="col-lg-8">
                    <textarea class="form-control ckeditor"
                              id="ck_1"
                              name="message"
                              rows="5"
                              toolbar="ilch_html"></textarea>
                </div>
            </div>
            <div class="col-lg-offset-2 col-lg-10">
                <?=$this->getSaveBar('profileSubmit', 'Contact') ?>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>
