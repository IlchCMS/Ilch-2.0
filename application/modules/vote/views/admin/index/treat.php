<?php

/** @var \Ilch\View $this */

/** @var Modules\Vote\Models\Vote|null $vote */
$vote = $this->get('vote');

/** @var Modules\Vote\Models\Result[] $voteRes */
$voteRes = $this->get('voteRes');
?>

<h1><?=($vote->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('groups') ? ' has-error' : '' ?>">
        <label for="group" class="col-xl-2 col-form-label">
            <?=$this->getTrans('participationGroup') ?>
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="group" name="groups[]"
                    data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>"
                    multiple>
                <option value="all" <?=in_array('all', $this->originalInput('group', explode(',', $vote->getGroups()))) ? 'selected="selected"' : '' ?>>
                    <?=$this->getTrans('groupAll') ?>
                </option>
                <?php foreach ($this->get('userGroupList') as $group) : ?>
                    <option value="<?=$group->getId() ?>" <?=in_array($group->getId(), $this->originalInput('group', explode(',', $vote->getGroups()))) ? ' selected' : '' ?>>
                        <?=$this->escape($group->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('access') ? ' has-error' : '' ?>">
        <label for="access" class="col-xl-2 col-form-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="access" name="access[]"
                    data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>"
                    multiple>
                <option value="all" <?=in_array('all', $this->originalInput('access', explode(',', $vote->getReadAccess()))) ? 'selected="selected"' : '' ?>>
                    <?=$this->getTrans('groupAll') ?>
                </option>
                <?php foreach ($this->get('userGroupList') as $groupList) : ?>
                    <?php if ($groupList->getId() != 1) : ?>
                        <option value="<?=$groupList->getId() ?>"<?=in_array($groupList->getId(), $this->originalInput('access', explode(',', $vote->getReadAccess()))) ? ' selected' : '' ?>><?=$groupList->getName() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('question') ? ' has-error' : '' ?>">
        <label for="question" class="col-xl-2 col-form-label">
            <?=$this->getTrans('question') ?>
        </label>
        <div class="col-xl-4">
            <input class="form-control"
                   type="text"
                   id="question"
                   name="question"
                   value="<?=$this->escape($this->originalInput('question', $vote->getQuestion())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('multiplereply') ? ' has-error' : '' ?>">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('multiplereply') ?>
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="multiplereply-on" name="multiplereply" value="1" <?=($this->originalInput('multiplereply', $vote->getMultipleReply())) ? 'checked="checked"' : '' ?> />
                <label for="multiplereply-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="multiplereply-off" name="multiplereply" value="0" <?=(!$this->originalInput('multiplereply', $vote->getMultipleReply())) ? 'checked="checked"' : '' ?> />
                <label for="multiplereply-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('reply') ? ' has-error' : '' ?>">
        <label for="reply" class="col-xl-2 col-form-label">
            <?=$this->getTrans('reply') ?>
        </label>
        <div id="reply-container" class="col-xl-4">
            <?php foreach ($voteRes as $counter => $voteResModel) : ?>
                <div class="mb-3 input-group">
                    <input type="text" name="reply[]" class="form-control" value="<?=$this->escape($voteResModel->getReply()) ?>">
                    <button type="button" class="btn btn-outline-success btn-add"><i class="fa-solid fa-plus"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-remove"><i class="fa-solid fa-minus"></i></button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?=($vote->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<script>
    $('#access').chosen();
    $('#group').chosen();

    $(document).ready(function() {
        // Funktion zum Hinzufügen eines neuen Reply-Feldes
        $(document).on('click', '.btn-add', function() {
            let $currentGroup = $(this).closest('.input-group');
            let $newGroup = $currentGroup.clone();

            // Setze den Wert des neuen Feldes auf leer
            $newGroup.find('input').val('');

            // Füge den neuen Reply-Feld-Block nach dem aktuellen Block hinzu
            $currentGroup.after($newGroup);

            // Überprüfe die Anzahl der Reply-Felder und aktualisiere den Löschen-Button
            updateRemoveButtons();
        });

        // Funktion zum Entfernen eines Reply-Feldes
        $(document).on('click', '.btn-remove', function() {
            let $currentGroup = $(this).closest('.input-group');

            // Entferne das aktuelle Reply-Feld
            $currentGroup.remove();

            // Überprüfe die Anzahl der Reply-Felder und aktualisiere den Löschen-Button
            updateRemoveButtons();
        });

        // Funktion zur Aktualisierung des Löschen-Buttons
        function updateRemoveButtons() {
            let $replyContainers = $('#reply-container .input-group');
            if ($replyContainers.length > 1) {
                $replyContainers.find('.btn-remove').show();
            } else {
                $replyContainers.find('.btn-remove').hide();
            }
        }

        // Initiale Überprüfung der Anzahl der Reply-Felder beim Laden der Seite
        updateRemoveButtons();
    });
</script>
