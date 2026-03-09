<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST">
    <?=$this->getTokenField() ?>

    <div class="row mb-3<?=$this->validation()->hasError('picturesPerPage') ? ' has-error' : '' ?>">
        <label for="picturesPerPageInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('picturesPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="picturesPerPageInput"
                   name="picturesPerPage"
                   min="1"
                   value="<?=$this->escape($this->originalInput('picturesPerPage', $this->get('picturesPerPage'))) ?>" />
        </div>
    </div>

    <h1><?=$this->getTrans('box') ?>: <?=$this->getTrans('pictureOfX') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('pictureOfXSource') ? ' has-error' : '' ?>">
        <label for="pictureOfXSource" class="col-xl-2 col-form-label">
            <?=$this->getTrans('pictureOfXSource') ?>:
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="pictureOfXSource"
                    name="pictureOfXSource[]"
                    data-placeholder="<?=$this->getTrans('selectGalleries') ?>"
                    multiple>
                <?php foreach ($this->get('galleries') as $gallery) : ?>
                    <option value="<?=$gallery->getId() ?>" <?=in_array($gallery->getId(), $this->originalInput('pictureOfXSource', $this->get('pictureOfXSource')) ?? []) ? 'selected="selected"' : '' ?>>
                        <?=$this->escape($gallery->getTitle()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('pictureOfXInterval') ? ' has-error' : '' ?>">
        <?php $selected = ($this->get('pictureOfXInterval')) ? 'selected="selected"' : ''?>
        <label for="pictureOfXInterval" class="col-xl-2 col-form-label">
            <?=$this->getTrans('pictureOfXInterval') ?>:
        </label>
        <div class="col-xl-4">
            <select class="form-select"
                    id="pictureOfXInterval"
                    name="pictureOfXInterval"
                    data-placeholder="<?=$this->getTrans('selectPictureOfXInterval') ?>">
                <option value="0" <?=(!$this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('everytime') ?></option>
                <option value="1" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 1) ? 'selected="selected"' : '' ?>><?=$this->getTrans('hourly') ?></option>
                <option value="2" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 2) ? 'selected="selected"' : '' ?>><?=$this->getTrans('daily') ?></option>
                <option value="3" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 3) ? 'selected="selected"' : '' ?>><?=$this->getTrans('weekly') ?></option>
                <option value="4" <?=($this->originalInput('pictureOfXInterval', $this->get('pictureOfXInterval')) == 4) ? 'selected="selected"' : '' ?>><?=$this->getTrans('monthly') ?></option>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('pictureOfXRandom') ? ' has-error' : '' ?>">
        <label for="pictureOfXRandom" class="col-xl-2 col-form-label">
            <?=$this->getTrans('pictureOfXRandom') ?>:
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="pictureOfXRandom-on" name="pictureOfXRandom" value="1" <?=$this->originalInput('pictureOfXRandom', $this->get('pictureOfXRandom')) ? 'checked="checked"' : '' ?> />
                <label for="pictureOfXRandom-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="pictureOfXRandom-off" name="pictureOfXRandom" value="0" <?=!$this->originalInput('pictureOfXRandom', $this->get('pictureOfXRandom')) ? 'checked="checked"' : '' ?> />
                <label for="pictureOfXRandom-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <link href="<?=$this->getModuleUrl('static/css/venoboxSettings.css') ?>" rel="stylesheet">

    <div class="venobox-grid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fa-solid fa-wand-magic-sparkles"></i> <?=$this->getTrans('venoboxSetting') ?></h1>
            <button type="button" id="resetVenobox" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-rotate-left"></i> Standard wiederherstellen
            </button>
        </div>

        <div class="venobox-cards-container">

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxFitView') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxFitViewDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="venoboxFitView-on" name="venoboxFitView" value="1" <?=$this->originalInput('venoboxFitView', $this->get('venoboxFitView')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxFitView-on" class="flipswitch-label"><?=$this->getTrans('on')?></label>
                            <input type="radio" class="flipswitch-input" id="venoboxFitView-off" name="venoboxFitView" value="0" <?=!$this->originalInput('venoboxFitView', $this->get('venoboxFitView')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxFitView-off" class="flipswitch-label"><?=$this->getTrans('off')?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxNumeration') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxNumerationDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="venoboxNumeration-on" name="venoboxNumeration" value="1" <?=$this->originalInput('venoboxNumeration', $this->get('venoboxNumeration')) ? 'checked="checked"' : '' ?>  />
                            <label for="venoboxNumeration-on" class="flipswitch-label"><?=$this->getTrans('on')?></label>
                            <input type="radio" class="flipswitch-input" id="venoboxNumeration-off" name="venoboxNumeration" value="0" <?=!$this->originalInput('venoboxNumeration', $this->get('venoboxNumeration')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxNumeration-off" class="flipswitch-label"><?=$this->getTrans('off')?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxInfiniteGallery') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxInfiniteGalleryDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="venoboxInfiniteGallery-on" name="venoboxInfiniteGallery" value="1" <?=$this->originalInput('venoboxInfiniteGallery', $this->get('venoboxInfiniteGallery')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxInfiniteGallery-on" class="flipswitch-label"><?=$this->getTrans('on')?></label>
                            <input type="radio" class="flipswitch-input" id="venoboxInfiniteGallery-off" name="venoboxInfiniteGallery" value="0" <?=!$this->originalInput('venoboxInfiniteGallery', $this->get('venoboxInfiniteGallery')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxInfiniteGallery-off" class="flipswitch-label"><?=$this->getTrans('off')?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxOverlayColor') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxOverlayColorDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="input-group">
                            <input class="form-control color" id="venoboxOverlayColor" name="venoboxOverlayColor" data-jscolor="" value="<?=$this->originalInput('venoboxOverlayColor', $this->get('venoboxOverlayColor')) ? : 'rgba(23,23,23,0.85)' ?>">
                            <span class="input-group-text reset-color" onclick="document.getElementById('venoboxOverlayColor').jscolor.fromString('rgba(23,23,23,0.85)')">
                                <i class="fa-solid fa-rotate-left"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxBorder') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxBorderDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <input type="text" class="form-control" id="venoboxBorder" name="venoboxBorder" pattern="([0-9]+px)?" value="<?=$this->escape($this->originalInput('venoboxBorder', $this->get('venoboxBorder'))) ?>" placeholder="z.B. 5px" />
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxBgcolor') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxBgcolorDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="input-group">
                            <input class="form-control color" id="venoboxBgcolor" name="venoboxBgcolor" data-jscolor="" value="<?=$this->originalInput('venoboxBgcolor', $this->get('venoboxBgcolor')) ? : '#ffffff' ?>">
                            <span class="input-group-text reset-color" onclick="document.getElementById('venoboxBgcolor').jscolor.fromString('ffffff')">
                                <i class="fa-solid fa-rotate-left"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxMaxWidth') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxMaxWidthDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <input type="text" class="form-control" id="venoboxMaxWidth" name="venoboxMaxWidth" pattern="([0-9]+(px|%|vw))?" value="<?=$this->escape($this->originalInput('venoboxMaxWidth', $this->get('venoboxMaxWidth'))) ?>" placeholder="z.B. 100%" />
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxSpinner') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxSpinnerDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <select class="form-select shadow-none" name="venoboxSpinner">
                            <?php $current = $this->get('venoboxSpinner') ?: 'bounce';
                            $types = ['plane', 'chase', 'bounce', 'wave', 'pulse', 'flow', 'swing', 'circle', 'circle-fade', 'grid', 'fold', 'wander'];
                            foreach($types as $type): ?>
                                <option value="<?=$type?>" <?=$current == $type ? 'selected' : ''?>><?=ucfirst($type)?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxSpinColor') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxSpinColorDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="input-group">
                            <input class="form-control color" id="venoboxSpinColor" name="venoboxSpinColor" data-jscolor="" value="<?=$this->get('venoboxSpinColor') ?: '#d2d2d2' ?>">
                            <span class="input-group-text reset-color" onclick="document.getElementById('venoboxSpinColor').jscolor.fromString('d2d2d2')"><i class="fa-solid fa-rotate-left"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxToolsBackground') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxToolsBackgroundDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="input-group">
                            <input class="form-control color" id="venoboxToolsBackground" name="venoboxToolsBackground" data-jscolor="" value="<?=$this->get('venoboxToolsBackground') ?: '#1C1C1C' ?>">
                            <span class="input-group-text reset-color" onclick="document.getElementById('venoboxToolsBackground').jscolor.fromString('1C1C1C')"><i class="fa-solid fa-rotate-left"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxToolsColor') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxToolsColorDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="input-group">
                            <input class="form-control color" id="venoboxToolsColor" name="venoboxToolsColor" data-jscolor="" value="<?=$this->get('venoboxToolsColor') ?: '#d2d2d2' ?>">
                            <span class="input-group-text reset-color" onclick="document.getElementById('venoboxToolsColor').jscolor.fromString('d2d2d2')"><i class="fa-solid fa-rotate-left"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxNavigation') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxNavigationDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="venoboxNavigation-on" name="venoboxNavigation" value="1" <?=$this->originalInput('venoboxNavigation', $this->get('venoboxNavigation')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxNavigation-on" class="flipswitch-label"><?=$this->getTrans('on')?></label>
                            <input type="radio" class="flipswitch-input" id="venoboxNavigation-off" name="venoboxNavigation" value="0" <?=!$this->originalInput('venoboxNavigation', $this->get('venoboxNavigation')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxNavigation-off" class="flipswitch-label"><?=$this->getTrans('off')?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxNavSpeed') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxNavSpeedDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <input type="number" class="form-control" name="venoboxNavSpeed" value="<?=$this->get('venoboxNavSpeed') ?: 300 ?>" step="50" min="0">
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxShare') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxShareDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="venoboxShare-on" name="venoboxShare" value="1" <?=$this->originalInput('venoboxShare', $this->get('venoboxShare')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxShare-on" class="flipswitch-label"><?=$this->getTrans('on')?></label>
                            <input type="radio" class="flipswitch-input" id="venoboxShare-off" name="venoboxShare" value="0" <?=!$this->originalInput('venoboxShare', $this->get('venoboxShare')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxShare-off" class="flipswitch-label"><?=$this->getTrans('off')?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxShareStyle') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxShareStyleDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <select class="form-select shadow-none" name="venoboxShareStyle">
                            <?php $style = $this->get('venoboxShareStyle') ?: 'bar'; ?>
                            <option value="bar" <?=$style == 'bar' ? 'selected' : ''?>>Balken (Bar)</option>
                            <option value="pill" <?=$style == 'pill' ? 'selected' : ''?>>Pille (Pill)</option>
                            <option value="block" <?=$style == 'block' ? 'selected' : ''?>>Block</option>
                            <option value="transparent" <?=$style == 'transparent' ? 'selected' : ''?>>Transparent</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxRatio') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxRatioDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <select class="form-select shadow-none" name="venoboxRatio">
                            <?php $ratio = $this->get('venoboxRatio') ?: '16x9';
                            foreach(['1x1', '4x3', '16x9', '21x9', 'full'] as $r): ?>
                                <option value="<?=$r?>" <?=$ratio == $r ? 'selected' : ''?>><?=$r?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-body">
                    <div class="card-info">
                        <label class="card-title"><?=$this->getTrans('venoboxTitleattr') ?></label>
                        <p class="card-description"><?=$this->getTrans('venoboxTitleattrDesc') ?></p>
                    </div>
                    <div class="card-action">
                        <div class="flipswitch">
                            <input type="radio" class="flipswitch-input" id="venoboxTitleattr-on" name="venoboxTitleattr" value="1" <?=$this->originalInput('venoboxTitleattr', $this->get('venoboxTitleattr')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxTitleattr-on" class="flipswitch-label"><?=$this->getTrans('on')?></label>
                            <input type="radio" class="flipswitch-input" id="venoboxTitleattr-off" name="venoboxTitleattr" value="0" <?=!$this->originalInput('venoboxTitleattr', $this->get('venoboxTitleattr')) ? 'checked="checked"' : '' ?> />
                            <label for="venoboxTitleattr-off" class="flipswitch-label"><?=$this->getTrans('off')?></label>
                            <span class="flipswitch-selection"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?=$this->getSaveBar() ?>
</form>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.min.js') ?>"></script>
<script src="<?=$this->getModuleUrl('static/js/main.js') ?>"></script>
