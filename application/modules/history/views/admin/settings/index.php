<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('menuSettings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <h1><?=$this->getTrans('desc_order') ?></h1>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('desc_order') ? 'has-error' : '' ?>">
        <label for="desc_order" class="col-lg-2 control-label">
            <?=$this->getTrans('desc_orderText') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="desc_order-on" name="desc_order" value="1" <?=($this->originalInput('desc_order', $this->get('desc_order'))) ? 'checked="checked"' : '' ?> />
                <label for="desc_order-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="desc_order-off" name="desc_order" value="0" <?=(!$this->originalInput('desc_order', $this->get('desc_order'))) ? 'checked="checked"' : '' ?> />
                <label for="desc_order-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
