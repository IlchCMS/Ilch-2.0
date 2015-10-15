<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <legend><?=$this->getTrans('menuSettings') ?></legend>
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="event_height" class="col-lg-2 control-label">
            <?=$this->getTrans('imageHeight') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_height"
                   type="text"
                   id="event_height"
                   class="form-control required"
                   value="<?=$this->get('event_height') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_width" class="col-lg-2 control-label">
            <?=$this->getTrans('imageWidth') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_width"
                   type="text"
                   id="event_width"
                   class="form-control required"
                   value="<?=$this->get('event_width') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_size" class="col-lg-2 control-label">
            <?=$this->getTrans('imageSizeBytes') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_size"
                   type="text"
                   id="event_size"
                   class="form-control required"
                   value="<?=$this->get('event_size') ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="event_filetypes" class="col-lg-2 control-label">
            <?=$this->getTrans('imageAllowedFileExtensions') ?>:
        </label>
        <div class="col-lg-2">
            <input name="event_filetypes"
                   type="text"
                   id="event_filetypes"
                   class="form-control required"
                   value="<?=$this->get('event_filetypes') ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
