<?php $vote = $this->get('vote'); ?>

<h1><?=($vote != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="group" class="col-lg-2 control-label">
            <?=$this->getTrans('participationGroup') ?>
        </label>
        <div class="col-lg-4">
            <select name="group"
                    id="group"
                    class="form-control">
                <option value="0" <?=($vote != '' AND $this->escape($vote->getGroup()) == 0) ? 'selected="selected"' : '' ?>>
                    <?=$this->getTrans('groupAll') ?>
                </option>
                <?php foreach($this->get('groups') as $group): ?>
                    <option value="<?=$group->getId() ?>" <?=($vote != '' AND $this->escape($vote->getGroup()) == $group->getId()) ? 'selected="selected"' : '' ?>>
                        <?=$this->escape($group->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('question') ? 'has-error' : '' ?>">
        <label for="question" class="col-lg-2 control-label">
            <?=$this->getTrans('question') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="question"
                   name="question"
                   value="<?php if ($vote != '') { echo $this->escape($vote->getQuestion()); } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('reply') ? 'has-error' : '' ?>">
        <label for="reply" class="col-lg-2 control-label">
            <?=$this->getTrans('reply') ?>
        </label>
        <?php if ($vote != ''): ?>
            <?php $resultMapper = new \Modules\Vote\Mappers\Result(); ?>
            <?php $voteRes = $resultMapper->getVoteRes($vote->getId()); ?>
            <?php $countRes = count($voteRes); ?>
            <?php $i = 0; ?>
            <div class="col-lg-4">
                <?php foreach ($voteRes as $voteRes): ?>
                    <?php $i++; ?>
                    <div class="form-group input-group">
                        <input type="text" name="reply[]" class="form-control" value="<?=$this->escape($voteRes->getReply()) ?>">
                        <span class="input-group-btn">
                            <?php if ($i == $countRes): ?>
                                <button type="button" class="btn btn-success btn-add">+</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger btn-remove">-</button>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="col-lg-4">
                <div class="form-group input-group">
                    <input type="text" name="reply[]" class="form-control">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success btn-add">+</button>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?=($vote != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<script>
(function ($) {
    $(function () {
        var addFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            var $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-success btn-add btn-danger btn-remove')
                .html('–');

            $formGroupClone.find('input').val('');
            $formGroupClone.insertAfter($formGroup);

            var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', true);
            }
        };

        var removeFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');

            var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', false);
            }

            $formGroup.remove();
        };

        var countFormGroup = function ($form) {
            return $form.find('.form-group').length;
        };

        $(document).on('click', '.btn-add', addFormGroup);
        $(document).on('click', '.btn-remove', removeFormGroup);
    });
})(jQuery);
</script>
