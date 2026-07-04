<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('messagesPerPageAdmincenter') ? ' has-error' : '' ?>">
        <label for="messagesPerPageAdmincenter" class="col-xl-2 col-form-label">
            <?=$this->getTrans('messagesPerPageAdmincenter') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="messagesPerPageAdmincenter"
                   name="messagesPerPageAdmincenter"
                   min="1"
                   value="<?=$this->originalInput('messagesPerPageAdmincenter', $this->get('messagesPerPageAdmincenter')) ?>">
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('messagesPerPage') ? ' has-error' : '' ?>">
        <label for="messagesPerPage" class="col-xl-2 col-form-label">
            <?=$this->getTrans('messagesPerPage') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="messagesPerPage"
                   name="messagesPerPage"
                   min="1"
                   value="<?=$this->originalInput('messagesPerPage', $this->get('messagesPerPage')) ?>">
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('maxtextlength') ? ' has-error' : '' ?>">
        <label for="maxtextlength" class="col-xl-2 col-form-label">
            <?=$this->getTrans('maximumTextLength') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="maxtextlength"
                   name="maxtextlength"
                   min="20"
                   value="<?=$this->originalInput('maxtextlength', $this->get('maxtextlength')) ?>">
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('writeAccess') ? ' has-error' : '' ?>">
        <label for="writeAccess" class="col-xl-2 col-form-label">
            <?=$this->getTrans('writeAccess') ?>
        </label>
        <div class="col-xl-3">
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
    <div class="row mb-3<?=$this->validation()->hasError('floodInterval') ? ' has-error' : '' ?>">
        <label for="floodInterval" class="col-xl-2 col-form-label">
            <?=$this->getTrans('floodInterval') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="floodInterval"
                   name="floodInterval"
                   min="0"
                   value="<?=$this->originalInput('floodInterval', $this->get('floodInterval')) ?>">
        </div>
    </div>
    <h1><?=$this->getTrans('boxSettings') ?></h1>
    <div class="row mb-3<?=$this->validation()->hasError('autoRefreshInterval') ? ' has-error' : '' ?>">
        <label for="autoRefreshInterval" class="col-xl-2 col-form-label">
            <?=$this->getTrans('autoRefreshInterval') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="autoRefreshInterval"
                   name="autoRefreshInterval"
                   min="0"
                   value="<?=$this->originalInput('autoRefreshInterval', $this->get('autoRefreshInterval')) ?>">
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('limit') ? ' has-error' : '' ?>">
        <label for="limit" class="col-xl-2 col-form-label">
            <?=$this->getTrans('numberOfMessagesDisplayed') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="limit"
                   name="limit"
                   min="1"
                   value="<?=$this->originalInput('limit', $this->get('limit')) ?>">
        </div>
    </div>
    <h1><?=$this->getTrans('designSettings') ?></h1>
    <?php
    $colorFields = [
        'designBackgroundColor' => '#ffffff',
        'designTextColor' => '#212529',
        'designNameColor' => '#0d6efd',
        'designBoxBackgroundColor' => '#ffffff',
        'designButtonColor' => '#6c757d',
        'designButtonTextColor' => '#ffffff',
        'designInputBackgroundColor' => '#ffffff',
        'designInputTextColor' => '#212529',
    ];
    ?>
    <?php foreach ($colorFields as $colorField => $colorFallback) : ?>
        <?php $colorValue = $this->get($colorField) ?>
        <div class="row mb-3">
            <label for="<?=$colorField ?>" class="col-xl-2 col-form-label">
                <?=$this->getTrans($colorField) ?>
            </label>
            <div class="col-xl-1">
                <input type="color"
                       class="form-control form-control-color"
                       id="<?=$colorField ?>"
                       name="<?=$colorField ?>"
                       value="<?=$this->escape($colorValue !== '' ? $colorValue : $colorFallback) ?>">
            </div>
            <div class="col-xl-3">
                <div class="form-check mt-2">
                    <input type="checkbox"
                           class="form-check-input"
                           id="<?=$colorField ?>Default"
                           name="<?=$colorField ?>Default"
                           value="1"
                           <?=$colorValue === '' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="<?=$colorField ?>Default">
                        <?=$this->getTrans('useThemeDefault') ?>
                    </label>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row mb-3<?=$this->validation()->hasError('designFontSize') ? ' has-error' : '' ?>">
        <label for="designFontSize" class="col-xl-2 col-form-label">
            <?=$this->getTrans('designFontSize') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="designFontSize"
                   name="designFontSize"
                   min="0"
                   max="50"
                   value="<?=$this->originalInput('designFontSize', $this->get('designFontSize')) ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label for="showAvatars" class="col-xl-2 col-form-label">
            <?=$this->getTrans('showAvatars') ?>
        </label>
        <div class="col-xl-3">
            <div class="form-check form-switch mt-2">
                <input type="checkbox"
                       class="form-check-input"
                       id="showAvatars"
                       name="showAvatars"
                       value="1"
                       <?=$this->get('showAvatars') ? 'checked' : '' ?>>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="customCss" class="col-xl-2 col-form-label">
            <?=$this->getTrans('customCss') ?>
        </label>
        <div class="col-xl-6">
            <textarea class="form-control font-monospace"
                      style="resize: vertical"
                      id="customCss"
                      name="customCss"
                      rows="6"
                      placeholder=".shoutbox-messages td { }"><?=$this->escape($this->originalInput('customCss', $this->get('customCss'))) ?></textarea>
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
