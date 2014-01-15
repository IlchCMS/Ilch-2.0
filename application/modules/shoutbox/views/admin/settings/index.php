<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <legend><?php echo $this->trans('settings'); ?></legend>
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="shoutboxSettings" class="col-lg-2 control-label">
            <?php echo $this->trans('numberOfMessagesDisplayed'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="limit"
                   name="limit"
                   type="text"
                   value="<?php echo $this->get('limit'); ?>"
                   required />
        </div>
    </div>
    <div class="form-group">
        <label for="shoutboxSettings" class="col-lg-2 control-label">
            <?php echo $this->trans('maximumWordLength'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="maxwordlength"
                   name="maxwordlength"
                   type="text"
                   value="<?php echo $this->get('maxwordlength'); ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar()?>
</form>
