<?php

/** @var \Ilch\View $this */
?>
<style>
    .shoutbox-color-field {
        background-color: var(--bs-body-bg);
    }

    .shoutbox-color-field.is-theme-default .shoutbox-color-preview-value {
        display: none;
    }

    .shoutbox-color-field.is-theme-default label {
        opacity: .65;
    }

    /* Checkerboard behind the color so a low opacity is actually visible. */
    .shoutbox-color-preview {
        position: relative;
        display: inline-block;
        flex: 0 0 auto;
        width: 2.25rem;
        height: 2.25rem;
        overflow: hidden;
        border: 1px solid var(--bs-border-color);
        border-radius: var(--bs-border-radius);
        background-color: #fff;
        background-image: linear-gradient(45deg, #ccc 25%, transparent 25%),
                          linear-gradient(-45deg, #ccc 25%, transparent 25%),
                          linear-gradient(45deg, transparent 75%, #ccc 75%),
                          linear-gradient(-45deg, transparent 75%, #ccc 75%);
        background-size: 12px 12px;
        background-position: 0 0, 0 6px, 6px -6px, -6px 0;
    }

    .shoutbox-color-preview-value {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    .shoutbox-color-field .form-range {
        min-width: 4rem;
    }

    .shoutbox-opacity-output {
        min-width: 2.75rem;
        text-align: right;
    }
</style>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row g-3 mb-3">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-gears"></i> <?=$this->getTrans('generalSettings') ?>
                </div>
                <div class="card-body">
                    <div class="mb-3<?=$this->validation()->hasError('messagesPerPage') ? ' has-error' : '' ?>">
                        <label for="messagesPerPage" class="form-label">
                            <?=$this->getTrans('messagesPerPage') ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="messagesPerPage"
                               name="messagesPerPage"
                               min="1"
                               value="<?=$this->originalInput('messagesPerPage', $this->get('messagesPerPage')) ?>">
                    </div>
                    <div class="mb-3<?=$this->validation()->hasError('messagesPerPageAdmincenter') ? ' has-error' : '' ?>">
                        <label for="messagesPerPageAdmincenter" class="form-label">
                            <?=$this->getTrans('messagesPerPageAdmincenter') ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="messagesPerPageAdmincenter"
                               name="messagesPerPageAdmincenter"
                               min="1"
                               value="<?=$this->originalInput('messagesPerPageAdmincenter', $this->get('messagesPerPageAdmincenter')) ?>">
                    </div>
                    <div class="mb-3<?=$this->validation()->hasError('maxtextlength') ? ' has-error' : '' ?>">
                        <label for="maxtextlength" class="form-label">
                            <?=$this->getTrans('maximumTextLength') ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="maxtextlength"
                               name="maxtextlength"
                               min="20"
                               value="<?=$this->originalInput('maxtextlength', $this->get('maxtextlength')) ?>">
                    </div>
                    <div class="mb-0<?=$this->validation()->hasError('writeAccess') ? ' has-error' : '' ?>">
                        <label for="writeAccess" class="form-label">
                            <?=$this->getTrans('writeAccess') ?>
                        </label>
                        <select class="choices-select form-control"
                                id="writeAccess"
                                name="writeAccess[]"
                                data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>"
                                multiple>
                            <?php
                            /** @var \Modules\User\Models\Group $groupList */
                            foreach ($this->get('userGroupList') as $groupList) : ?>
                                <option value="<?=$groupList->getId() ?>"
                                    <?php $writeAccess = $this->originalInput('writeAccess', $this->get('writeAccess')) ?>
                                    <?php $writeAccess = is_array($writeAccess) ? $writeAccess : explode(',', $writeAccess);
                                    foreach ($writeAccess as $access) {
                                        if ($groupList->getId() == $access) {
                                            echo 'selected="selected"';
                                            break;
                                        }
                                    }
                                    ?>>
                                    <?=$groupList->getName() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-bullhorn"></i> <?=$this->getTrans('boxSettings') ?>
                </div>
                <div class="card-body">
                    <div class="mb-3<?=$this->validation()->hasError('limit') ? ' has-error' : '' ?>">
                        <label for="limit" class="form-label">
                            <?=$this->getTrans('numberOfMessagesDisplayed') ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="limit"
                               name="limit"
                               min="1"
                               value="<?=$this->originalInput('limit', $this->get('limit')) ?>">
                    </div>
                    <div class="mb-3<?=$this->validation()->hasError('autoRefreshInterval') ? ' has-error' : '' ?>">
                        <label for="autoRefreshInterval" class="form-label">
                            <?=$this->getTrans('autoRefreshInterval') ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="autoRefreshInterval"
                               name="autoRefreshInterval"
                               min="0"
                               value="<?=$this->originalInput('autoRefreshInterval', $this->get('autoRefreshInterval')) ?>">
                    </div>
                    <div class="mb-0<?=$this->validation()->hasError('floodInterval') ? ' has-error' : '' ?>">
                        <label for="floodInterval" class="form-label">
                            <?=$this->getTrans('floodInterval') ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="floodInterval"
                               name="floodInterval"
                               min="0"
                               value="<?=$this->originalInput('floodInterval', $this->get('floodInterval')) ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-palette"></i> <?=$this->getTrans('designSettings') ?>
                </div>
                <div class="card-body">
                    <?php
                    $view = $this;
                    /**
                     * Renders one color setting as a self contained block: preview, color
                     * picker, optional opacity slider and the theme default switch.
                     */
                    $renderColorField = function (string $colorField, string $colorFallback, bool $withOpacity = false) use ($view) {
                        $colorValue = (string)$view->get($colorField);
                        $isDefault = $colorValue === '';
                        $opacity = \Modules\Shoutbox\Libs\DesignCss::getOpacity($colorValue);
                        // <input type="color"> only knows #rrggbb, the alpha channel is set by the range below.
                        $pickerValue = $colorValue !== '' ? substr($colorValue, 0, 7) : $colorFallback; ?>
                        <div class="col">
                            <div class="shoutbox-color-field h-100 p-3 border rounded<?=$isDefault ? ' is-theme-default' : '' ?>">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="shoutbox-color-preview">
                                        <span class="shoutbox-color-preview-value"
                                              style="background-color: <?=$view->escape($pickerValue) ?>; opacity: <?=sprintf('%.2F', $opacity / 100) ?>"></span>
                                    </span>
                                    <label for="<?=$colorField ?>" class="form-label mb-0 flex-grow-1">
                                        <?=$view->getTrans($colorField) ?>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <input type="color"
                                           class="form-control form-control-color"
                                           id="<?=$colorField ?>"
                                           name="<?=$colorField ?>"
                                           value="<?=$view->escape($pickerValue) ?>"
                                           <?=$isDefault ? 'disabled' : '' ?>>
                                    <?php if ($withOpacity) : ?>
                                        <label for="<?=$colorField ?>Opacity" class="form-label mb-0 small text-body-secondary text-nowrap">
                                            <?=$view->getTrans('designOpacity') ?>
                                        </label>
                                        <input type="range"
                                               class="form-range"
                                               id="<?=$colorField ?>Opacity"
                                               name="<?=$colorField ?>Opacity"
                                               min="0"
                                               max="100"
                                               step="1"
                                               value="<?=$opacity ?>"
                                               <?=$isDefault ? 'disabled' : '' ?>>
                                        <span class="small text-body-secondary text-nowrap shoutbox-opacity-output"><?=$opacity ?> %</span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="<?=$colorField ?>Default"
                                           name="<?=$colorField ?>Default"
                                           value="1"
                                           <?=$isDefault ? 'checked' : '' ?>>
                                    <label class="form-check-label small" for="<?=$colorField ?>Default">
                                        <?=$view->getTrans('useThemeDefault') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php };
                    ?>
                    <h6 class="border-bottom pb-2 mb-3"><?=$this->getTrans('designMessagesSection') ?></h6>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-3 g-3 mb-4">
                        <?php
                        $renderColorField('designBackgroundColor', '#ffffff', true);
                        $renderColorField('designTextColor', '#212529');
                        $renderColorField('designNameColor', '#0d6efd');
                        ?>
                    </div>
                    <h6 class="border-bottom pb-2 mb-3"><?=$this->getTrans('designBoxSection') ?></h6>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-3 g-3 mb-4">
                        <?php
                        $renderColorField('designBoxBackgroundColor', '#ffffff', true);
                        $renderColorField('designInputBackgroundColor', '#ffffff', true);
                        $renderColorField('designInputTextColor', '#212529');
                        $renderColorField('designButtonColor', '#6c757d', true);
                        $renderColorField('designButtonTextColor', '#ffffff');
                        ?>
                    </div>
                    <h6 class="border-bottom pb-2 mb-3"><?=$this->getTrans('designMiscSection') ?></h6>
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="mb-3<?=$this->validation()->hasError('designFontSize') ? ' has-error' : '' ?>">
                                <label for="designFontSize" class="form-label mb-1">
                                    <?=$this->getTrans('designFontSize') ?>
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="designFontSize"
                                       name="designFontSize"
                                       min="0"
                                       max="50"
                                       style="max-width: 8rem"
                                       value="<?=$this->originalInput('designFontSize', $this->get('designFontSize')) ?>">
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="showAvatars"
                                       name="showAvatars"
                                       value="1"
                                       <?=$this->get('showAvatars') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="showAvatars">
                                    <?=$this->getTrans('showAvatars') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <label for="customCss" class="form-label mb-1">
                                <?=$this->getTrans('customCss') ?>
                            </label>
                            <textarea class="form-control font-monospace"
                                      style="resize: vertical"
                                      id="customCss"
                                      name="customCss"
                                      rows="6"
                                      placeholder=".shoutbox-messages td { }"><?=$this->escape($this->originalInput('customCss', $this->get('customCss'))) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $(document).ready(function() {
        new Choices('#writeAccess', {
            ...choicesOptions,
            searchEnabled: true
        })

        // Keep preview, percentage and the disabled state of each color field in sync.
        document.querySelectorAll('.shoutbox-color-field').forEach(function (field) {
            var picker = field.querySelector('input[type="color"]');
            var range = field.querySelector('input[type="range"]');
            var themeDefault = field.querySelector('input[type="checkbox"]');
            var preview = field.querySelector('.shoutbox-color-preview-value');
            var output = field.querySelector('.shoutbox-opacity-output');

            var update = function () {
                var opacity = range ? Number(range.value) : 100;

                preview.style.backgroundColor = picker.value;
                preview.style.opacity = opacity / 100;
                field.classList.toggle('is-theme-default', themeDefault.checked);
                picker.disabled = themeDefault.checked;

                if (range) {
                    range.disabled = themeDefault.checked;
                    output.textContent = opacity + ' %';
                }
            };

            picker.addEventListener('input', update);
            themeDefault.addEventListener('change', update);
            if (range) {
                range.addEventListener('input', update);
            }
        });
    });
</script>
