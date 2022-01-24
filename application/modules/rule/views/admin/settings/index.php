<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="threadsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('showallonstart') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="showallonstart-on" name="showallonstart" value="1" <?=($this->get('showallonstart') == '1') ? 'checked="checked"' : '' ?> />
                <label for="showallonstart-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="showallonstart-off" name="showallonstart" value="0" <?=($this->get('showallonstart') != '1') ? 'checked="checked"' : '' ?> />
                <label for="showallonstart-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
