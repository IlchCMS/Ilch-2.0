<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('picturesPerPage') ? 'has-error' : '' ?>">
        <label for="picturesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('picturesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="picturesPerPageInput"
                   name="picturesPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('picturesPerPage')) ?>" />
        </div>
    </div>
    <h1><?=$this->getTrans('box') ?>: <?=$this->getTrans('pictureOfX') ?></h1>
    <div class="form-group <?=$this->validation()->hasError('pictureOfXSource') ? 'has-error' : '' ?>">
        <label for="pictureOfXSource" class="col-lg-2 control-label">
            <?=$this->getTrans('pictureOfXSource') ?>:
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="pictureOfXSource"
                    name="pictureOfXSource[]"
                    data-placeholder="<?=$this->getTrans('selectGalleries') ?>"
                    multiple>
                <?php foreach ($this->get('galleries') as $gallery): ?>
                    <option value="<?=$gallery->getId() ?>"
                        <?php foreach ($this->get('pictureOfXSource') as $galleryId) {
                                if ($gallery->getId() == $galleryId) {
                                    echo 'selected="selected"';
                                    break;
                                }
                            }
                        ?>>
                        <?=$this->escape($gallery->getTitle()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('pictureOfXInterval') ? 'has-error' : '' ?>">
        <?php $selected = ($this->get('pictureOfXInterval')) ? 'selected="selected"' : ''?>
        <label for="pictureOfXInterval" class="col-lg-2 control-label">
            <?=$this->getTrans('pictureOfXInterval') ?>:
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="pictureOfXInterval"
                    name="pictureOfXInterval"
                    data-placeholder="<?=$this->getTrans('selectPictureOfXInterval') ?>">
                    <option value="0" <?=(!$this->get('pictureOfXInterval')) ? 'selected="selected"' : '' ?>><?=$this->getTrans('everytime') ?></option>
                    <option value="1" <?=($this->get('pictureOfXInterval') == 1) ? 'selected="selected"' : '' ?>><?=$this->getTrans('hourly') ?></option>
                    <option value="2" <?=($this->get('pictureOfXInterval') == 2) ? 'selected="selected"' : '' ?>><?=$this->getTrans('daily') ?></option>
                    <option value="3" <?=($this->get('pictureOfXInterval') == 3) ? 'selected="selected"' : '' ?>><?=$this->getTrans('weekly') ?></option>
                    <option value="4" <?=($this->get('pictureOfXInterval') == 4) ? 'selected="selected"' : '' ?>><?=$this->getTrans('monthly') ?></option>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('pictureOfXRandom') ? 'has-error' : '' ?>">
        <label for="pictureOfXRandom" class="col-lg-2 control-label">
            <?=$this->getTrans('pictureOfXRandom') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="pictureOfXRandom-on" name="pictureOfXRandom" value="1" <?php if ($this->get('pictureOfXRandom') == '1') { echo 'checked="checked"'; } ?> />
                <label for="pictureOfXRandom-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="pictureOfXRandom-off" name="pictureOfXRandom" value="0" <?php if ($this->get('pictureOfXRandom') != '1') { echo 'checked="checked"'; } ?> />
                <label for="pictureOfXRandom-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <h1>Venobox Einstellungen</h1>
    <div class="form-group <?=$this->validation()->hasError('venoboxNumeration') ? 'has-error' : '' ?>">
        <label for="venoboxNumeration" class="col-lg-2 control-label">
            <?=$this->getTrans('venoboxNumeration') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="venoboxNumeration-on" name="venoboxNumeration" value="1" <?php if ($this->get('venoboxNumeration') == '1') { echo 'checked="checked"'; } ?>  />
                <label for="venoboxNumeration-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>

                <input type="radio" class="flipswitch-input" id="venoboxNumeration-off" name="venoboxNumeration" value="0" <?php if ($this->get('venoboxNumeration') != '1') { echo 'checked="checked"'; } ?> />
                <label for="venoboxNumeration-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('venoboxOverlayColor') ? 'has-error' : '' ?>">
        <label for="color" class="col-lg-2 control-label">
            <?=$this->getTrans('venoboxOverlayColor') ?>:
        </label>
        <div class="col-lg-2 input-group">
            <input class="form-control color {hash:true}"
                   id="venoboxOverlayColor"
                   name="venoboxOverlayColor"
                   value="<?php if ($this->get('venoboxOverlayColor') != '') { echo $this->get('venoboxOverlayColor'); } else { echo '#ffffff'; } ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('venoboxOverlayColor').color.fromString('ffffff')"></span>
            </span>
        </div>

    </div>

    <div class="form-group <?=$this->validation()->hasError('venoboxInfinigall') ? 'has-error' : '' ?>">
        <label for="venoboxInfinigall" class="col-lg-2 control-label">
            <?=$this->getTrans('venoboxInfinigall') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="venoboxInfinigall-on" name="venoboxInfinigall" value="1" <?php if ($this->get('venoboxInfinigall') == '1') { echo 'checked="checked"'; } ?> />
                <label for="venoboxInfinigall-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>

                <input type="radio" class="flipswitch-input" id="venoboxInfinigall-off" name="venoboxInfinigall" value="0" <?php if ($this->get('venoboxInfinigall') != '1') { echo 'checked="checked"'; } ?> />
                <label for="venoboxInfinigall-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('venoboxBgcolor') ? 'has-error' : '' ?>">
        <label for="color" class="col-lg-2 control-label">
            <?=$this->getTrans('venoboxBgcolor') ?>:
        </label>
        <div class="col-lg-2 input-group">
            <input class="form-control color {hash:true}"
                   id="venoboxBgcolor"
                   name="venoboxBgcolor"
                   value="<?php if ($this->get('venoboxBgcolor') != '') { echo $this->get('venoboxBgcolor'); } else { echo '#ffffff'; } ?>">
            <span class="input-group-addon">
                <span class="fa fa-undo" onclick="document.getElementById('venoboxBgcolor').color.fromString('ffffff')"></span>
            </span>
        </div>

    </div>

    <div class="form-group <?=$this->validation()->hasError('venoboxBorder') ? 'has-error' : '' ?>">
        <label for="venoboxBorder" class="col-lg-2 control-label">
            <?=$this->getTrans('venoboxBorder') ?>:
        </label>
        <div class="col-lg-2 input-group">
            <input type="text"
                   class="form-control"
                   id="venoboxBorder"
                   name="venoboxBorder"
                   pattern="[0-9]+px"
                   value="<?=($this->get('venoboxBorder')) ? $this->escape($this->get('venoboxBorder')) : $this->originalInput('venoboxBorder') ?>" />
            <div class="form-text">Zum Beispiel: 15px</div>
        </div>

    </div>

    <div class="form-group <?=$this->validation()->hasError('venoboxTitleattr') ? 'has-error' : '' ?>">
        <label for="venoboxTitleattr" class="col-lg-2 control-label">
            <?=$this->getTrans('venoboxTitleattr') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="venoboxTitleattr-on" name="venoboxTitleattr" value="title" <?php if ($this->get('venoboxTitleattr') == 'title') { echo 'checked="checked"'; } ?> />
                <label for="venoboxTitleattr-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>

                <input type="radio" class="flipswitch-input" id="venoboxTitleattr-off" name="venoboxTitleattr" value="" <?php if ($this->get('venoboxTitleattr') != 'title') { echo 'checked="checked"'; } ?> />
                <label for="venoboxTitleattr-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <?=$this->getSaveBar() ?>
</form>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.js') ?>"></script>
<script>
    $('#pictureOfXSource').chosen();
</script>
