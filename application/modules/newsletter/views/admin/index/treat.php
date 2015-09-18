<legend><?=$this->getTrans('add') ?></legend>
<?php if ($this->get('emails') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="form-group">
            <label for="subject" class="col-lg-2 control-label">
                <?=$this->getTrans('subject') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       name="subject"
                       id="subject" />
            </div>
        </div>
        <div class="form-group">
            <label for="text" class="col-lg-2 control-label">
                <?=$this->getTrans('text') ?>:
            </label>
            <div class="col-lg-10">
                <textarea class="form-control ckeditor"
                          name="text"
                          id="ck_1"
                          toolbar="ilch_html"
                          rows="5"></textarea>
            </div>
        </div>
        <?=$this->getSaveBar('send') ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noEmails') ?>
<?php endif; ?>
