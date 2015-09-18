<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="imprintStyle" class="col-lg-2 control-label">
            <?=$this->getTrans('site') ?>:
        </label>
        <div class="col-lg-2">
            <div class="flipswitch">  
                <input type="radio" class="flipswitch-input" name="imprintStyle" value="0" id="imprint-privat" <?php if ($this->get('imprintStyle') == '0') { echo 'checked="checked"'; } ?> />  
                <label for="imprint-privat" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('private') ?></label>  
                <input type="radio" class="flipswitch-input" name="imprintStyle" value="1" id="imprint-company" <?php if ($this->get('imprintStyle') == '1') { echo 'checked="checked"'; } ?> />  
                <label for="imprint-company" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('company') ?></label>  
                <span class="flipswitch-selection"></span>  
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
