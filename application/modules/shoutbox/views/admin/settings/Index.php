<legend><?php echo $this->trans('settings'); ?></legend>
<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="shoutboxSettings" class="col-lg-2 control-label">
            <?php echo $this->trans('numberOfMessagesDisplayed'); ?>:
        </label>
        <div class="form-group">
            <div class="col-lg-1">
                <input class="form-control"
                       id="limit"
                       name="limit"
                       type="text"
                       value="<?php echo $this->get('limit'); ?>"
                       required />
            </div>
        </div>
        <label for="shoutboxSettings" class="col-lg-2 control-label">
            <?php echo $this->trans('maximumWordLength'); ?>:
        </label>
        <div class="form-group">
            <div class="col-lg-1">
                <input class="form-control"
                       id="maxwordlength"
                       name="maxwordlength"
                       type="text"
                       value="<?php echo $this->get('maxwordlength'); ?>"
                       required />
            </div>
        </div>
    </div> 
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php echo $this->trans('saveButton'); ?>
        </button>
    </div>
</form>
