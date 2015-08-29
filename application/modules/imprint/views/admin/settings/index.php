<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="imprintStyle" class="col-lg-2 control-label">
            <?=$this->getTrans('site') ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="imprintStyle"
                       id="imprintStyle"
                       value="0"
                    <?php if ($this->get('imprintStyle') == '0') { echo 'checked="checked"';} ?> /> <?=$this->getTrans('private') ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="imprintStyle"
                       id="imprintStyle"
                       value="1"
                    <?php if ($this->get('imprintStyle') == '1') { echo 'checked="checked"';} ?>> <?=$this->getTrans('company') ?>
                </label>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
