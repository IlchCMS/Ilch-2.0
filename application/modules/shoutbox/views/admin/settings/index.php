<?php

/** @var \Ilch\View $this */
?>
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
                    $renderColorField = function (string $colorField, string $colorFallback) use ($view) {
                        $colorValue = $view->get($colorField); ?>
                        <div class="mb-3">
                            <label for="<?=$colorField ?>" class="form-label mb-1">
                                <?=$view->getTrans($colorField) ?>
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color"
                                       class="form-control form-control-color"
                                       id="<?=$colorField ?>"
                                       name="<?=$colorField ?>"
                                       value="<?=$view->escape($colorValue !== '' ? $colorValue : $colorFallback) ?>">
                                <div class="form-check mb-0">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="<?=$colorField ?>Default"
                                           name="<?=$colorField ?>Default"
                                           value="1"
                                           <?=$colorValue === '' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="<?=$colorField ?>Default">
                                        <?=$view->getTrans('useThemeDefault') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php };
                    ?>
                    <div class="row g-4">
                        <div class="col-xl-4">
                            <h6 class="border-bottom pb-2 mb-3"><?=$this->getTrans('designMessagesSection') ?></h6>
                            <?php
                            $renderColorField('designBackgroundColor', '#ffffff');
                            $renderColorField('designTextColor', '#212529');
                            $renderColorField('designNameColor', '#0d6efd');
                            ?>
                        </div>
                        <div class="col-xl-4">
                            <h6 class="border-bottom pb-2 mb-3"><?=$this->getTrans('designBoxSection') ?></h6>
                            <?php
                            $renderColorField('designBoxBackgroundColor', '#ffffff');
                            $renderColorField('designButtonColor', '#6c757d');
                            $renderColorField('designButtonTextColor', '#ffffff');
                            $renderColorField('designInputBackgroundColor', '#ffffff');
                            $renderColorField('designInputTextColor', '#212529');
                            ?>
                        </div>
                        <div class="col-xl-4">
                            <h6 class="border-bottom pb-2 mb-3"><?=$this->getTrans('designMiscSection') ?></h6>
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
                            <div class="mb-3">
                                <div class="form-check form-switch">
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
                            <div class="mb-0">
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
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $(document).ready(function() {
        new Choices('#writeAccess', {
            ...choicesOptions,
            searchEnabled: true
        })
    });
</script>
