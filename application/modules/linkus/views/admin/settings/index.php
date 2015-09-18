<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="html" class="col-lg-2 control-label">
            <?=$this->getTrans('showHtml') ?>:
        </label>
        <div class="col-lg-4">
            <label class="checkbox-inline">
                <input type="radio" 
                       name="html" 
                       id="html_yes" 
                       value="1" 
                       <?php if ($this->get('linkus_html') == '1') { echo 'checked="checked"';} ?>>
                       <label for="html_yes"><?=$this->getTrans('yes') ?></label>
            </label>
            <label class="checkbox-inline">
                <input type="radio" 
                       name="html" 
                       id="html_no" 
                       value="0" 
                       <?php if ($this->get('linkus_html') == '0') { echo 'checked="checked"';} ?>>
                       <label for="html_no"><?=$this->getTrans('no') ?></label>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="bbcode" class="col-lg-2 control-label">
            <?=$this->getTrans('showBBBCode') ?>:
        </label>
        <div class="col-lg-4">
            <label class="checkbox-inline">
                <input type="radio" 
                       name="bbcode" 
                       id="bbcode_yes" 
                       value="1" 
                       <?php if ($this->get('linkus_bbcode') == '1') { echo 'checked="checked"';} ?>>
                       <label for="bbcode_yes"><?=$this->getTrans('yes') ?></label>
            </label>
            <label class="checkbox-inline">
                <input type="radio" 
                       name="bbcode" 
                       id="bbcode_no" 
                       value="0" 
                       <?php if ($this->get('linkus_bbcode') == '0') { echo 'checked="checked"';} ?>>
                       <label for="bbcode_no"><?=$this->getTrans('no') ?></label>
            </label>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
