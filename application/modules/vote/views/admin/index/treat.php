<?php

/** @var \Ilch\View $this */

/** @var Modules\Vote\Models\Vote|null $vote */
$vote = $this->get('vote');
?>

<h1><?=($vote->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('groups') ? 'has-error' : '' ?>">
        <label for="group" class="col-lg-2 control-label">
            <?=$this->getTrans('participationGroup') ?>
        </label>
        <div class="col-lg-4">
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
    <div class="row mb-3 <?=$this->validation()->hasError('access') ? 'has-error' : '' ?>">
        <label for="access" class="col-lg-2 control-label">
            <?=$this->getTrans('visibleFor') ?>
        </label>
        <div class="col-lg-4">
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
    <div class="row mb-3 <?=$this->validation()->hasError('question') ? 'has-error' : '' ?>">
        <label for="question" class="col-lg-2 control-label">
            <?=$this->getTrans('question') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="question"
                   name="question"
                   value="<?=$this->escape($this->originalInput('question', $vote->getQuestion())) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('multiplereply') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('multiplereply') ?>
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="multiplereply-on" name="multiplereply" value="1" <?=($this->originalInput('multiplereply', $vote->getMultipleReply())) ? 'checked="checked"' : '' ?> />
                <label for="multiplereply-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="multiplereply-off" name="multiplereply" value="0" <?=(!$this->originalInput('multiplereply', $vote->getMultipleReply())) ? 'checked="checked"' : '' ?> />
                <label for="multiplereply-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('reply') ? 'has-error' : '' ?>">
        <label for="reply" class="col-lg-2 control-label">
            <?=$this->getTrans('reply') ?>
        </label>
        <?php if ($vote->getId()) : ?>
            <?php $resultMapper = new \Modules\Vote\Mappers\Result(); ?>
            <?php $voteRes = $resultMapper->getVoteRes($vote->getId()); ?>
            <?php $countRes = count($voteRes); ?>
            <?php $i = 0; ?>
            <div class="col-lg-4">
                <?php foreach ($voteRes as $voteResModel) : ?>
                    <?php $i++; ?>
                    <div class="mb-3 input-group">
                        <input type="text" name="reply[]" class="form-control" value="<?=$this->escape($voteResModel->getReply()) ?>">
                        <span class="input-group-btn">
                            <?php if ($i == $countRes) : ?>
                                <button type="button" class="btn btn-success btn-add">+</button>
                            <?php else : ?>
                                <button type="button" class="btn btn-danger btn-remove">-</button>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="col-lg-4">
                <div class="mb-3 input-group">
                    <input type="text" name="reply[]" class="form-control">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success btn-add">+</button>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?=($vote->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<script>
$('#access').chosen();
$('#group').chosen();

(function ($) {
    $(function () {
        let addFormGroup = function (event) {
            event.preventDefault();

            let $formGroup = $(this).closest('.form-group');
            let $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            let $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-success btn-add btn-danger btn-remove')
                .html('–');

            $formGroupClone.find('input').val('');
            $formGroupClone.insertAfter($formGroup);

            let $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', true);
            }
        };

        let removeFormGroup = function (event) {
            event.preventDefault();

            let $formGroup = $(this).closest('.form-group');
            let $multipleFormGroup = $formGroup.closest('.multiple-form-group');

            let $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', false);
            }

            $formGroup.remove();
        };

        let countFormGroup = function ($form) {
            return $form.find('.form-group').length;
        };

        $(document).on('click', '.btn-add', addFormGroup);
        $(document).on('click', '.btn-remove', removeFormGroup);
    });
})(jQuery);
</script>
