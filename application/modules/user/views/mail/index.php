<?php if ($this->getUser() AND $this->getUser()->getId() != $this->getRequest()->getParam('user')): ?>
    <?php $user = $this->get('user'); ?>
    <?php if ($user->getOptMail() == 1): ?>
        <h1><?=$this->getTrans('mailTo') ?> <?=$user->getName() ?></h1>
        <form method="POST" class="form-horizontal">
            <?=$this->getTokenField() ?>
            <div class="form-group <?=$this->validation()->hasError('subject') ? 'has-error' : '' ?>">
                <label for="subject" class="col-lg-2 control-label">
                    <?=$this->getTrans('subject') ?>:
                </label>
                <div class="col-lg-8">
                    <input type="text"
                           class="form-control"
                           id="subject"
                           name="subject"
                           value="<?=$this->originalInput('subject') ?>" />
                </div>
            </div>
            <div class="form-group <?=$this->validation()->hasError('message') ? 'has-error' : '' ?>">
                <label for="message" class="col-lg-2 control-label">
                    <?=$this->getTrans('message') ?>:
                </label>
                <div class="col-lg-8">
                    <textarea class="form-control"
                              id="message"
                              name="message"
                              rows="8"><?=$this->originalInput('message') ?></textarea>
                </div>
            </div>
            <div class="col-lg-offset-2 col-lg-10">
                <?=$this->getSaveBar('profileSubmit', 'Contact') ?>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>
