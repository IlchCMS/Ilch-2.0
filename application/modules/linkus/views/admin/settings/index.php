<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="html" class="col-lg-2 control-label">
            <?=$this->getTrans('showHtml') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">  
                <input type="radio" class="flipswitch-input" name="html" value="1" id="html-yes" <?php if ($this->get('linkus_html') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="html-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>  
                <input type="radio" class="flipswitch-input" name="html" value="0" id="html-no" <?php if ($this->get('linkus_html') != '1') { echo 'checked="checked"'; } ?> />  
                <label for="html-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>  
                <span class="flipswitch-selection"></span>  
            </div>  
        </div>
    </div>
<div class="form-group">
        <label for="bbcode" class="col-lg-2 control-label">
            <?=$this->getTrans('showBBBCode') ?>:
        </label>
    <div class="col-lg-4">
        <div class="flipswitch">  
            <input type="radio" class="flipswitch-input" name="bbcode" value="1" id="bbcode-yes" <?php if ($this->get('linkus_bbcode') == '1') { echo 'checked="checked"'; } ?> />  
            <label for="bbcode-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>  
            <input type="radio" class="flipswitch-input" name="bbcode" value="0" id="bbcode-no" <?php if ($this->get('linkus_bbcode') != '1') { echo 'checked="checked"'; } ?> />  
            <label for="bbcode-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>  
            <span class="flipswitch-selection"></span>  
        </div>
    </div>
</div>
    <?=$this->getSaveBar() ?>
</form>