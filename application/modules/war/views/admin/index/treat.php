<?php

/** @var \Ilch\View $this */

use Ilch\Date;

/** @var \Modules\War\Models\War $entry */
$entry = $this->get('war');
?>
<link href="<?=$this->getStaticUrl('js/tempus-dominus/dist/css/tempus-dominus.min.css') ?>" rel="stylesheet">
<h1><?=(!$entry->getId()) ? $this->getTrans('menuActionNewWar') : $this->getTrans('manageWar') ?></h1>
<?php if ($this->get('groups') != '' && $this->get('enemies') != '') : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="row mb-3<?=$this->validation()->hasError('warEnemy') ? ' has-error' : '' ?>">
            <label for="warEnemy" class="col-xl-2 col-form-label">
                <?=$this->getTrans('warEnemy') ?>:
            </label>
            <div class="col-xl-4">
                <select class="form-select" id="warEnemy" name="warEnemy">
                    <optgroup label="<?=$this->getTrans('enemysName') ?>">
                        <?php
                        /** @var \Modules\War\Models\Enemy $enemy */
                        foreach ($this->get('enemies') as $enemy) : ?>
                            <option value="<?=$enemy->getId() ?>" <?=($this->originalInput('warEnemy', ($entry->getId() ? $entry->getWarEnemy() : 0))) == $enemy->getId() ? 'selected=""' : '' ?>><?=$this->escape($enemy->getEnemyName()) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warGroup') ? ' has-error' : '' ?>">
            <label for="warGroup" class="col-xl-2 col-form-label">
                <?=$this->getTrans('warGroup') ?>:
            </label>
            <div class="col-xl-4">
                <select class="form-select" id="warGroup" name="warGroup">
                    <optgroup label="<?=$this->getTrans('groupsName') ?>">
                        <?php
                        /** @var \Modules\War\Models\Group $group */
                        foreach ($this->get('groups') as $group) : ?>
                            <option value="<?=$group->getId() ?>" <?=($this->originalInput('warGroup', ($entry->getId() ? $entry->getWarGroup() : 0))) == $group->getId() ? 'selected=""' : '' ?>><?=$this->escape($group->getGroupName()) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warTime') ? ' has-error' : '' ?>">
            <label for="warTimeInput" class="col-lg-2 col-form-label">
                <?=$this->getTrans('warTime') ?>:
            </label>
            <div id="warTime" class="input-group ilch-date date form_datetime col-xl-4">
                <input type="text"
                       class="form-control"
                       id="warTimeInput"
                       name="warTime"
                       size="16"
                       value="<?=$this->escape($this->originalInput('warTime', ($entry->getId() ? (new Date($entry->getWarTime()))->format("d.m.Y H:i") : ''))) ?>"
                       readonly />
                <span class="input-group-text">
                    <span class="fa-solid fa-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warMap') ? ' has-error' : '' ?>">
            <label for="warMapInput" class="col-xl-2 col-form-label">
                <?=$this->getTrans('warMap') ?>
            </label>
            <div class="col-xl-4">
                <select class="chosen-select form-control" id="warMapInput" name="warMap[]" data-placeholder="<?=$this->getTrans('selectAssignedMaps') ?>" multiple>
                    <?php
                    /** @var \Modules\War\Models\Maps $mapsList */
                    foreach ($this->get('mapsList') ?? [] as $mapsList) : ?>
                        <option value="<?=$mapsList->getId() ?>" <?=in_array($mapsList->getId(), $this->originalInput('warMap', $this->get('warMaps'))) ? 'selected=""' : '' ?>><?=$mapsList->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warServer') ? ' has-error' : '' ?>">
            <label for="warServerInput" class="col-xl-2 col-form-label">
                <?=$this->getTrans('warServer') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="warServerInput"
                       name="warServer"
                       value="<?=$this->escape($this->originalInput('warServer', ($entry->getId() ? $entry->getWarServer() : ''))) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warPassword') ? ' has-error' : '' ?>">
            <label for="warPasswordInput" class="col-lg-2 col-form-label">
                <?=$this->getTrans('warPassword') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="warPasswordInput"
                       name="warPassword"
                       value="<?=$this->escape($this->originalInput('warPassword', ($entry->getId() ? $entry->getWarPassword() : ''))) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warXonx') ? ' has-error' : '' ?>">
            <label for="warXonx" class="col-xl-2 col-form-label">
                <label for="warXonxNew">
                    <?=$this->getTrans('warXonx') ?>:
                </label>
            </label>
            <div class="col-xl-2">
                <select class="form-select" id="warXonx" name="warXonx">
                    <optgroup label="<?=$this->getTrans('warXonx') ?>">
                        <option value="new" <?=($this->originalInput('warXonx', ($entry->getId() ? $entry->getWarXonx() : 'new'))) == 'new' ? 'selected=""' : '' ?>><?=$this->getTrans('new') ?></option>
                        <?php if ($this->get('warOptXonxs') != '') : ?>
                            <?php
                            /** @var \Modules\War\Models\War $opt */
                            foreach ($this->get('warOptXonxs') as $opt) : ?>
                                <option value="<?=$opt->getWarXonx() ?>" <?=($this->originalInput('warXonx', ($entry->getId() ? $entry->getWarXonx() : 'new'))) == $opt->getWarXonx() ? 'selected=""' : '' ?>><?=$this->escape($opt->getWarXonx()) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-xl-2">
                <input type="text"
                       class="form-control"
                       style=""
                       id="warXonxNew"
                       name="warXonxNew"
                       value="<?=$this->escape($this->originalInput('warXonxNew')) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warGame') ? ' has-error' : '' ?>">
            <label for="warGame" class="col-xl-2 col-form-label">
                <label for="warGameNew">
                    <?=$this->getTrans('warGame') ?>:
                </label>
            </label>
            <div class="col-xl-2">
                <select class="form-select" id="warGame" name="warGame">
                    <optgroup label="<?=$this->getTrans('warGame') ?>">
                        <option value="new" <?=($this->originalInput('warGame', ($entry->getId() ? $entry->getWarGame() : 'new'))) == 'new' ? 'selected=""' : '' ?>><?=$this->getTrans('warNew') ?></option>
                        <?php if ($this->get('warOptGames') != '') : ?>
                            <?php
                            /** @var \Modules\War\Models\War $opt */
                            foreach ($this->get('warOptGames') as $opt) : ?>
                                <option value="<?=$opt->getWarGame() ?>" <?=($this->originalInput('warGame', ($entry->getId() ? $entry->getWarGame() : 'new'))) == $opt->getWarGame() ? 'selected=""' : '' ?>><?=$this->escape($opt->getWarGame()) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-xl-2">
                <input type="text"
                       class="form-control"
                       style=""
                       id="warGameNew"
                       name="warGameNew"
                       value="<?=$this->escape($this->originalInput('warGameNew')) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('warMatchtype') ? ' has-error' : '' ?>">
            <label for="warMatchtype" class="col-xl-2 col-form-label">
                <label for="warMatchtypeNew">
                    <?=$this->getTrans('warMatchtype') ?>:
                </label>
            </label>
            <div class="col-xl-2">
                <select class="form-select" id="warMatchtype" name="warMatchtype">
                    <optgroup label="<?=$this->getTrans('warMatchtype') ?>">
                        <option value="new" <?=($this->originalInput('warMatchtype', ($entry->getId() ? $entry->getWarMatchtype() : 'new'))) == 'new' ? 'selected=""' : '' ?>><?=$this->getTrans('new') ?></option>
                        <?php if ($this->get('warOptMatchtypes') != '') : ?>
                            <?php
                            /** @var \Modules\War\Models\War $opt */
                            foreach ($this->get('warOptMatchtypes') as $opt) : ?>
                                <option value="<?=$opt->getWarMatchtype() ?>" <?=($this->originalInput('warMatchtype', ($entry->getId() ? $entry->getWarMatchtype() : 'new'))) == $opt->getWarMatchtype() ? 'selected=""' : '' ?>><?=$this->escape($opt->getWarMatchtype()) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </optgroup>
                </select>
            </div>
            <div class="col-xl-2">
                <input type="text"
                       class="form-control"
                       style=""
                       id="warMatchtypeNew"
                       name="warMatchtypeNew"
                       value="<?=$this->escape($this->originalInput('warMatchtypeNew')) ?>" />
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('lastAcceptTime') ? ' has-error' : '' ?>">
            <label for="lastAcceptTimeInput" class="col-xl-2 col-form-label">
                <?=$this->getTrans('lastAcceptTime') ?>:
            </label>
            <div class="col-xl-4">
                <input type="text"
                       class="form-control"
                       id="lastAcceptTimeInput"
                       name="lastAcceptTime"
                       value="<?=$this->escape($this->originalInput('lastAcceptTime', ($entry->getId() ? $entry->getLastAcceptTime() : 0))) ?>" />
            </div>
        </div>
        <?php if ($entry->getId()) : ?>
            <h1><?=$this->getTrans('warResult') ?></h1>
            <div id="games"></div>
        <?php else : ?>
            <h1><?=$this->getTrans('warResultInfo') ?></h1>
            <div class="row mb-3">
                <div class="col-xl-2">
                    <?=$this->getTrans('warResultInfo') ?>:
                </div>
                <div class="col-xl-4">
                    <span><?=$this->getTrans('warResultInfoText') ?></span>
                </div>
            </div>
        <?php endif; ?>
        <h1><label for="ck_1"><?=$this->getTrans('warReport') ?></label></h1>
        <div class="row mb-3<?=$this->validation()->hasError('warReport') ? ' has-error' : '' ?>">
            <div class="offset-xl-2 col-xl-8">
                <textarea class="form-control ckeditor"
                          id="ck_1"
                          name="warReport"
                          toolbar="ilch_html"><?=$this->escape($this->originalInput('warReport', ($entry->getId() ? $entry->getWarReport() : ''))) ?></textarea>
            </div>
        </div>
        <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
            <label for="access" class="col-xl-2 col-form-label">
                <?=$this->getTrans('visibleFor') ?>
            </label>
            <div class="col-xl-6">
                <select class="chosen-select form-control" id="access" name="groups[]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                    <option value="all" <?=in_array('all', $this->originalInput('groups', $this->get('groups'))) ? 'selected="selected"' : '' ?>><?=$this->getTrans('groupAll') ?></option>
                    <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                        <?php if ($groupList->getId() != 1) : ?>
                            <option value="<?=$groupList->getId() ?>" <?=in_array($groupList->getId(), $this->originalInput('groups', $this->get('groups'))) ? 'selected=""' : '' ?>><?=$groupList->getName() ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php if ($this->get('calendarShow') == 1) : ?>
        <div class="row mb-3<?=$this->validation()->hasError('calendarShow') ? ' has-error' : '' ?>">
            <label for="calendarShow" class="col-xl-2 col-form-label">
                <?=$this->getTrans('calendarShow') ?>:
            </label>
            <div class="col-xl-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="calendarShow-yes" name="calendarShow" value="1" <?=($this->originalInput('calendarShow', ($entry->getId() ? $entry->getShow() : true))) ? 'checked="checked"' : '' ?> />
                    <label for="calendarShow-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                    <input type="radio" class="flipswitch-input" id="calendarShow-no" name="calendarShow" value="0"  <?=(!$this->originalInput('calendarShow', ($entry->getId() ? $entry->getShow() : true))) ? 'checked="checked"' : '' ?> />
                    <label for="calendarShow-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <h1><?=$this->getTrans('warStatus') ?></h1>
        <div class="row mb-3<?=$this->validation()->hasError('warStatus') ? ' has-error' : '' ?>">
            <label for="warStatus" class="col-xl-2 col-form-label">
                <?=$this->getTrans('warStatus') ?>:
            </label>
            <div class="col-xl-4">
                <div class="flipswitch">
                    <input type="radio" class="flipswitch-input" id="warStatus-open" name="warStatus" value="1" <?=($this->originalInput('warStatus', ($entry->getId() ? $entry->getWarStatus() : 1)) == 1) ? 'checked="checked"' : '' ?> />
                    <label for="warStatus-open" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('warStatusOpen') ?></label>
                    <input type="radio" class="flipswitch-input" id="warStatus-close" name="warStatus" value="2"  <?=($this->originalInput('warStatus', ($entry->getId() ? $entry->getWarStatus() : 1)) == 2) ? 'checked="checked"' : '' ?> />
                    <label for="warStatus-close" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('warStatusClose') ?></label>
                    <span class="flipswitch-selection"></span>
                </div>
            </div>
        </div>
        <?=($entry->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
    </form>
<?php else : ?>
    <?=$this->getTranslator()->trans('firstGroupEnemy') ?>
<?php endif; ?>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/popper/dist/umd/popper.min.js') ?>" charset="UTF-8"></script>
<script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/js/tempus-dominus.min.js') ?>" charset="UTF-8"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getStaticUrl('js/tempus-dominus/dist/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>" charset="UTF-8"></script>
<?php endif; ?>
<script>
$('#warMapInput').chosen();
$('#access').chosen();
$(document).ready(function () {
    if ("<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>" !== 'en') {
        tempusDominus.loadLocale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>);
        tempusDominus.locale(tempusDominus.locales.<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>.name);
    }

    new tempusDominus.TempusDominus(document.getElementById('warTime'), {
        display: {
            sideBySide: true,
            calendarWeeks: true,
            buttons: {
                today: true,
                close: true
            }
        },
        localization: {
            locale: "<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>",
            startOfTheWeek: 1,
            format: "dd.MM.yyyy HH:mm"
        },
        stepping: 15
    });

    diasableXonx();
    diasableGame();
    diasableMatchtype();
    loadGames();

    document.getElementById('warXonx').onchange = diasableXonx;
    document.getElementById('warGame').onchange = diasableGame;
    document.getElementById('warMatchtype').onchange = diasableMatchtype;

    function diasableXonx() {
        if (document.getElementById('warXonx').value === 'new') {
            document.getElementById("warXonxNew").style.display = "block";
            document.getElementById("warXonx").style.margin = "0 0 5px";
        } else {
            document.getElementById("warXonxNew").style.display = "none";
            document.getElementById("warXonxNew").value = '';
        }
    }

    function diasableGame() {
        if (document.getElementById('warGame').value === 'new') {
            document.getElementById("warGameNew").style.display = "block";
            document.getElementById("warGame").style.margin = "0 0 5px";
        } else {
            document.getElementById("warGameNew").style.display = "none";
            document.getElementById("warGameNew").value = '';
        }
    }

    function diasableMatchtype() {
        if (document.getElementById('warMatchtype').value === 'neu') {
            document.getElementById("warMatchtypeNew").style.display = "block";
            document.getElementById("warMatchtype").style.margin = "0 0 5px";
        } else {
            document.getElementById("warMatchtypeNew").style.display = "none";
            document.getElementById("warMatchtypeNew").value = '';
        }
    }

    function loadGames() {
        $('#games').load('<?=$this->getUrl(array_merge(['controller' => 'ajax', 'action' => 'game'], ($entry->getId() ? ['id' => $entry->getId()] : []))) ?>');
    }
});
</script>
