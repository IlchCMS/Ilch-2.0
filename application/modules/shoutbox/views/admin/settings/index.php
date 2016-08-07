<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="limit" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfMessagesDisplayed') ?>:
        </label>
        <div class="col-lg-1">
            <input class="form-control" type="number" id="limit" name="limit" min="1" value="<?=$this->get('limit') ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="maxwordlength" class="col-lg-2 control-label">
            <?=$this->getTrans('maximumWordLength') ?>:
        </label>
        <div class="col-lg-1">
            <input class="form-control" type="number" id="maxwordlength" name="maxwordlength" min="1" value="<?=$this->get('maxwordlength') ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="maxtextlength" class="col-lg-2 control-label">
            <?=$this->getTrans('maximumTextLength') ?>:
        </label>
        <div class="col-lg-1">
            <input class="form-control" type="number" id="maxtextlength" name="maxtextlength" min="1" value="<?=$this->get('maxtextlength') ?>">
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
