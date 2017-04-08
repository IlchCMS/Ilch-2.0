<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('imprintStyle') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('site') ?>:
        </div>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="imprint-privat" name="imprintStyle" value="0" <?php if ($this->get('imprintStyle') == '0') { echo 'checked="checked"'; } ?> />
                <label for="imprint-privat" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('private') ?></label>
                <input type="radio" class="flipswitch-input" id="imprint-company" name="imprintStyle" value="1" <?php if ($this->get('imprintStyle') == '1') { echo 'checked="checked"'; } ?> />
                <label for="imprint-company" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('company') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
