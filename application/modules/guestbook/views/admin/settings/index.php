<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="guestbookSettings" class="col-lg-2 control-label">
            <?=$this->getTrans('entrySettings') ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="entrySettings"
                       id="entrySettings"
                       value="0"
                <?php if ($this->get('setfree') == '0') { echo 'checked="checked"';} ?> /> <?=$this->getTrans('no') ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="entrySettings"
                       value="1"
                <?php if ($this->get('setfree') == '1') { echo 'checked="checked"';} ?>> <?=$this->getTrans('yes') ?>
                </label>
            </div>
        </div>
    </div> 
    <?=$this->getSaveBar()?>
</form>
