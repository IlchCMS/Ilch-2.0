<h1><?=$this->getTrans('menuHtaccess') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('modRewrite') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('modRewrite') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="modRewrite-on" name="modRewrite" value="1" <?php if ($this->get('modRewrite') == '1') { echo 'checked="checked"'; } ?> />
                <label for="modRewrite-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="modRewrite-off" name="modRewrite" value="0" <?php if ($this->get('modRewrite') != '1') { echo 'checked="checked"'; } ?> />
                <label for="modRewrite-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-6">
            <textarea class="form-control"
                      id="htaccess"
                      name="htaccess"
                      rows="20"
                      title="<?=$this->getTrans('adjustHtaccess') ?>"><?=$this->get('htaccess') ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
