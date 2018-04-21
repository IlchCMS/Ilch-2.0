<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('imprintStyle') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('site') ?>:
        </div>
        <div class="col-lg-2">
            <select class="form-control" id="imprintStyle" name="imprintStyle">
                <option value="0" <?php if ($this->get('imprintStyle') == '0') { echo 'selected="selected"'; } ?>><?=$this->getTrans('private') ?></option>
                <option value="1" <?php if ($this->get('imprintStyle') == '1') { echo 'selected="selected"'; } ?>><?=$this->getTrans('club') ?></option>
                <option value="2" <?php if ($this->get('imprintStyle') == '2') { echo 'selected="selected"'; } ?>><?=$this->getTrans('company') ?></option>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
