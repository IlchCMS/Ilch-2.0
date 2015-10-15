<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="guestbookSettings" class="col-lg-2 control-label">
            <?=$this->getTrans('entrySettings') ?>:
        </label>
        <div class="col-lg-2">
            <div class="flipswitch">  
                <input type="radio" class="flipswitch-input" name="entrySettings" value="1" id="setfree-yes" <?php if ($this->get('setfree') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="setfree-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>  
                <input type="radio" class="flipswitch-input" name="entrySettings" value="0" id="setfree-no" <?php if ($this->get('setfree') != '1') { echo 'checked="checked"'; } ?> />  
                <label for="setfree-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>  
                <span class="flipswitch-selection"></span>  
            </div>  
        </div>
    </div> 
    <?=$this->getSaveBar()?>
</form>
