<legend><?php echo $this->trans('settings'); ?></legend>
<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="guestbookSettings" class="col-lg-2 control-label">
            <?php echo $this->trans('entrySettings'); ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="entrySettings"
                       id="entrySettings"
                       value="1"
                <?php if ($this->get('setfree') == '1') { echo 'checked="checked"';} ?> /> <?php echo $this->trans('no'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="entrySettings"
                       value="0"
                <?php if ($this->get('setfree') != '1') { echo 'checked="checked"';} ?>> <?php echo $this->trans('yes'); ?>
                </label>
            </div>
        </div>
    </div> 
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php echo $this->trans('saveButton'); ?>
        </button>
    </div>
</form>
