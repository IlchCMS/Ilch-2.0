<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="shoutboxSettings" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfMessagesDisplayed') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="limit"
                   name="limit"
                   type="text"
                   value="<?=$this->get('limit') ?>"
                   required />
        </div>
    </div>
    <div class="form-group">
        <label for="shoutboxSettings" class="col-lg-2 control-label">
            <?=$this->getTrans('maximumWordLength') ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="maxwordlength"
                   name="maxwordlength"
                   type="text"
                   value="<?=$this->get('maxwordlength') ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
