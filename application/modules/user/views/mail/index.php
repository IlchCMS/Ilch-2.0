<?php if ($this->getUser() && $this->getUser()->getId() != $this->getRequest()->getParam('user')): ?>
    <?php $user = $this->get('user'); ?>
    <?php if ($user->getOptMail() == 1): ?>
        <h1><?=$this->getTrans('mailTo') ?> <?=$user->getName() ?></h1>
        <form method="POST">
            <?=$this->getTokenField() ?>
            <div class="row mb-3 <?=$this->validation()->hasError('subject') ? 'has-error' : '' ?>">
                <label for="subject" class="col-xl-2 control-label">
                    <?=$this->getTrans('subject') ?>:
                </label>
                <div class="col-xl-8">
                    <input type="text"
                           class="form-control"
                           id="subject"
                           name="subject"
                           value="<?=$this->originalInput('subject') ?>" />
                </div>
            </div>
            <div class="row mb-3 <?=$this->validation()->hasError('message') ? 'has-error' : '' ?>">
                <label for="message" class="col-xl-2 control-label">
                    <?=$this->getTrans('message') ?>:
                </label>
                <div class="col-xl-8">
                    <textarea class="form-control"
                              id="message"
                              name="message"
                              rows="8"><?=$this->originalInput('message') ?></textarea>
                </div>
            </div>
            <div class="offset-xl-2 col-xl-10">
                <?=$this->getSaveBar('profileSubmit', 'Contact') ?>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>
