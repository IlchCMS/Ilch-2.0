<?php

use Ilch\View;
use Modules\User\Models\ProfileField;
use Modules\User\Models\ProfileFieldTranslation;

/** @var View $this */
/** @var int $countOfProfileFields */
$countOfProfileFields = $this->get('countOfProfileFields');
/** @var ProfileField $profileField */
$profileField = $this->get('profileField');
/** @var ProfileFieldTranslation[] $profileFieldsTranslation */
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
/** @var string[] $localeList */
$localeList = $this->get('localeList');

/** @var int[] $localeList */
$multiArr = $this->get('multiTypes');

/** @var array $type */
$type = [
    0 => 'profileFieldField',
    1 => 'profileFieldCat',
    2 => 'profileFieldIcon',
    3 => 'profileFieldRadio',
    4 => 'profileFieldCheck',
    5 => 'profileFieldDrop',
    6 => 'profileFieldDate'
];
/** @var array $iconArray */
$iconArray = [
    0 => 'fa-regular fa-pen-to-square',
    1 => 'fa-solid fa-heading',
    2 => 'fa-solid fa-icons',
    3 => 'fa-regular fa-circle-check',
    4 => 'fa-regular fa-square-check',
    5 => 'fa-regular fa-square-caret-down',
    6 => 'fa-regular fa-calendar-days'
];
?>

<link href="<?=$this->getModuleUrl('static/css/profile-fields.css') ?>" rel="stylesheet">
<script>
    let indexList = [];
</script>

<h1>
    <?=($profileField->getId()) ? $this->getTrans('editProfileField') : $this->getTrans('addProfileField') ?>
    <a class="badge rounded-pill bg-secondary text-white" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>

<form action="" method="POST" id="profileFieldForm">
    <?=$this->getTokenField() ?>
    <input type="hidden"
           name="profileField[id]"
           value="<?=$profileField->getId() ?>" />
    <input type="hidden"
           name="profileField[position]"
           value="<?=($profileField->getId()) ? $profileField->getPosition() : $countOfProfileFields ?>" />

    <!-- select profilefield -->
    <div class="row mb-3">
        <label for="profileFieldType" class="col-xl-2 col-form-label">
            <?=$this->getTrans('profileFieldType') ?>
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <select class="form-select" id="profileFieldType" name="profileField[type]"<?=$profileField->getCore() ? ' disabled' : '' ?>>
                    <?php foreach ($type as $key => $value) : ?>
                        <option value="<?=$key ?>"
                            <?=($profileField->getId() && $profileField->getType() == $key) ? ' selected' : '' ?>
                            <?=($profileField->getId() && $profileField->getType() != $key) ? ' disabled' : '' ?>
                            >
                            <?=$this->getTrans($value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="input-group-text typeinfo">
                    <span class="<?=($profileField->getType() !== null) ? $iconArray[$profileField->getType()] : $iconArray[0] ?>"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- field description -->
    <div class="row mb-3">
        <label for="profileFieldDescription" class="col-lg-2 col-form-label">
            <?=$this->getTrans('profileFieldDescription') ?>
        </label>
        <div class="col-xl-4">
            <textarea class="form-control typedesc" id="profileFieldDescription" rows="2" readonly><?=($profileField->getType() !== null) ? $this->getTrans('profileFieldTypeDesc' . $profileField->getType()) : $this->getTrans('profileFieldTypeDesc0') ?></textarea>
        </div>
    </div>

    <!-- registration settings -->
    <div class="row mb-3">
        <label for="profileFieldShowOnRegistration" class="col-lg-2 col-form-label">
            <?=$this->getTrans('profileFieldShowOnRegistration') ?>
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="showOnRegistration-yes" name="profileField[showOnRegistration]" value="1"<?=($profileField->getRegistration() >= '1') ? ' checked="checked"' : '' ?> />
                <label for="showOnRegistration-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="showOnRegistration-no" name="profileField[showOnRegistration]" value="0"<?=($profileField->getRegistration() == '0') ? ' checked="checked"' : '' ?> />
                <label for="showOnRegistration-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3" id="showOnRegistrationRequired"<?=($profileField->getRegistration() == '0') ? ' hidden' : '' ?>>
        <label for="profileFieldShowOnRegistrationRequired" class="col-lg-2 col-form-label">
            <?=$this->getTrans('profileFieldShowOnRegistrationRequired') ?>
        </label>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="showOnRegistrationRequired-yes" name="profileField[showOnRegistrationRequired]" value="1"<?=($profileField->getRegistration() == '2') ? ' checked="checked"' : '' ?> />
                <label for="showOnRegistrationRequired-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="showOnRegistrationRequired-no" name="profileField[showOnRegistrationRequired]" value="0"<?=($profileField->getRegistration() != '2') ? ' checked="checked"' : '' ?> />
                <label for="showOnRegistrationRequired-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <!-- icon selection -->
    <div class="row mb-3" id="profileFieldIcons" <?=($profileField->getType() == 2) ? '' : 'hidden' ?>>
        <?php
        $icon = '';
        if ($profileField->getType() == 2) {
            $icon = ($profileField->getIcon() !== '') ? $profileField->getIcon() : $this->get('post')['symbol'] ?? '';
        }
        ?>
        <label for="profileFieldIcon" class="col-xl-2 col-form-label">
            <?=$this->getTrans('profileFieldIcon') ?>:
        </label>
        <div class="col-xl-4 input-group ilch-date">
            <span class="input-group-text">
                <span id="chosensymbol" class="<?=$icon ?>"></span>
            </span>
            <input type="text"
                   class="form-control"
                   id="profileFieldIcon"
                   name="profileField[icon]"
                   value="<?=$icon ?>"
                   readonly />
            <span class="input-group-text">
                <span class="fa-solid fa-arrow-pointer" data-bs-toggle="modal" data-bs-target="#symbolDialog"></span>
            </span>
        </div>
    </div>

    <!-- db key -->
    <div class="row mb-3">
        <label for="profileFieldKey" class="col-xl-2 col-form-label">
            <?=$this->getTrans('profileFieldKey') ?>
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control required"
                       id="profileFieldKey"
                       name="profileField[key]"
                       value="<?=$this->escape($profileField->getKey()) ?>"
                       <?=$profileField->getCore() ? 'disabled' : '' ?> />
                <span class="input-group-text">
                    <span class="fa-solid fa-info" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="<?=$this->getTrans('profileFieldKeyDesc') ?>"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- icon addition -->
    <div class="row mb-3" id="profileFieldAddition" <?=($profileField->getType() == 2) ? '' : 'hidden' ?>>
        <label for="profileFieldLinkAddition" class="col-xl-2 col-form-label">
            <?=$this->getTrans('profileFieldLinkAddition') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="profileFieldLinkAddition"
                   name="profileField[addition]"
                   value="<?=$this->escape($profileField->getAddition()) ?>" />
        </div>
    </div>

    <!-- translation fiels -->
    <div id="translations">
        <div class="row mb-3" id="profileFieldTrans">
            <label for="profileFieldTrans_name" class="col-lg-2 col-form-label">
                <?=$this->getTrans('profileFieldName') ?>
            </label>
            <div id="trans-container" class="col-xl-4">
                <?php foreach ($profileFieldsTranslation as $profileFieldTranslation) : ?>
                <div class="mb-3 input-group">
                    <input type="hidden" name="profileFieldTrans_field_id[]" value="<?=$profileField->getId() ?>" />
                    <input type="hidden" name="profileFieldTrans_oldLocale[]" value="<?=$profileFieldTranslation->getLocale() ?>" />

                    <select class="form-select selectTrans" name="profileFieldTrans_locale[]">
                        <option selected="selected" disabled><?=$this->getTrans('pleaseSelect') ?></option>
                        <?php foreach ($localeList as $key => $locale) : ?>
                            <option value="<?=$key ?>"<?=((isset($localeList[$profileFieldTranslation->getLocale()]) && $locale == $localeList[$profileFieldTranslation->getLocale()]) ? ' selected' : ''); ?>><?=$locale ?></option>
                            <?php next($localeList);
                        endforeach; ?>
                    </select>
                    <input type="text"
                           class="form-control"
                           name="profileFieldTrans_name[]"
                           placeholder="<?=$this->getTrans('profileFieldName') ?>"
                           value="<?=$this->escape($profileFieldTranslation->getName()) ?>" />
                    <button type="button" class="btn btn-outline-success btn-addTrans"><i class="fa-solid fa-plus"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-removeTrans"><i class="fa-solid fa-minus"></i></button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- multi options -->
    <div class="row mb-3 profileFieldsMulti" <?=(in_array($profileField->getType(), $multiArr)) ? '' : 'hidden' ?>>
        <?php if (empty($profileField->getOptions())) : ?>
            <?php $profileField->setOptions('[""]');?>
        <?php endif; ?>
        <?php $options = json_decode($profileField->getOptions(), true); ?>
        <label for="profileFieldOptions" class="col-lg-2 col-form-label">
            <?=$this->getTrans('profileFieldOptions')  ?>
        </label>
        <div id="options-container" class="col-xl-4">
            <?php foreach ($options as $key => $value) : ?>
                <div class="mb-3 input-group">
                    <input type="text" name="profileFieldOptions[]" class="form-control required" value="<?=$this->escape($value) ?>" />
                    <button type="button" class="btn btn-outline-success btn-add"><i class="fa-solid fa-plus"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-remove"><i class="fa-solid fa-minus"></i></button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- save -->
    <?=$this->getSaveBar() ?>
</form>

<div class="modal fade" id="symbolDialog" tabindex="-1" role="dialog" aria-labelledby="symbolDialogTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="symbolDialogTitle"><?=$this->getTrans('chooseIcon') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="col-6"><button type="button" class="btn btn-outline-secondary" id="noIcon" data-bs-dismiss="modal"><?=$this->getTrans('noIcon') ?></button></div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?=$this->getTrans('close') ?></button>
            </div>
        </div>
    </div>
</div>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('profileFieldTransInfoText')) ?>

<script>
    $('#profileFieldForm').validate();

    $('[name="profileField[type]"]').click(function () {
        const keysArr = [<?php foreach ($multiArr as $key => $value) {
            echo ($key > 0 ? ', ' : '') . '\'' . $value . '\'';
                         } ?>];
        const thisKey = $(this).val();
        if (thisKey === "2") {
            $('#profileFieldIcons, #profileFieldAddition').removeAttr('hidden');
        } else {
            $('#profileFieldIcons, #profileFieldAddition').attr('hidden', '');
        }
        if (jQuery.inArray(thisKey, keysArr) !== -1) {
            $('.profileFieldsSingle').attr('hidden', '');
            $('.profileFieldsMulti').removeAttr('hidden');
        } else {
            $('.profileFieldsSingle').removeAttr('hidden');
            $('.profileFieldsMulti').attr('hidden', '');
        }
    });

    $('select#profileFieldType').change(function() {
        const typeKey = $('#profileFieldType').find(':selected').val();
        const typeDesc0 = '<?=$this->getTrans('profileFieldTypeDesc0') ?>';
        const typeDesc1 = '<?=$this->getTrans('profileFieldTypeDesc1') ?>';
        const typeDesc2 = '<?=$this->getTrans('profileFieldTypeDesc2') ?>';
        const typeDesc3 = '<?=$this->getTrans('profileFieldTypeDesc3') ?>';
        const typeDesc4 = '<?=$this->getTrans('profileFieldTypeDesc4') ?>';
        const typeDesc5 = '<?=$this->getTrans('profileFieldTypeDesc5') ?>';
        const typeDesc6 = '<?=$this->getTrans('profileFieldTypeDesc6') ?>';
        const typeDesc7 = '<?=$this->getTrans('profileFieldTypeDesc7') ?>';
        const iconArray = ['fa-regular fa-pen-to-square', 'fa-solid fa-heading', 'fa-solid fa-icons', 'fa-regular fa-circle-check', 'fa-regular fa-square-check', 'fa-regular fa-square-caret-down', 'fa-regular fa-calendar-days'];
        $('.typeinfo').html('<span class="'+iconArray[typeKey]+'"></span>');
        $('.typedesc').val(eval("typeDesc"+typeKey));
    });

    $(document).ready(function() {
        $(document).on('click', '.btn-addTrans', function() {
            let $currentGroup = $(this).closest('.input-group');
            let $newGroup = $currentGroup.clone();

            $newGroup.find('hidden').val('');
            $newGroup.find('input').val('');
            $newGroup.find('select').prop('selectedIndex', 0);

            $currentGroup.after($newGroup);

            updateRemoveTransButtons();

            attachChangeEvent();
        });

        function attachChangeEvent() {
            $('.selectTrans').off('change').on('change', function () {
                let selectedValue = $(this).val();
                let isDuplicate = false;

                $('.selectTrans').not(this).each(function () {
                    if ($(this).val() === selectedValue) {
                        isDuplicate = true;
                        return false;
                    }
                });

                if (isDuplicate) {
                    alert('<?=$this->getTrans('translationAlreadyExisting') ?>');
                    $(this).prop('selectedIndex', 0);
                }
            });
        }

        $(document).on('click', '.btn-removeTrans', function() {
            let $currentGroup = $(this).closest('.input-group');

            $currentGroup.remove();

            updateRemoveTransButtons();
        });

        function updateRemoveTransButtons() {
            let $replyContainers = $('#trans-container .input-group');
            if ($replyContainers.length > 1) {
                $replyContainers.find('.btn-removeTrans').show();
            } else {
                $replyContainers.find('.btn-removeTrans').hide();
            }
        }

        updateRemoveTransButtons();

        attachChangeEvent();
    });

    $("#symbolDialog").on('shown.bs.modal', function () {
        let content = JSON.parse(<?=json_encode(file_get_contents(ROOT_PATH . '/vendor/fortawesome/font-awesome/metadata/icons.json')) ?>);
        let icons = [];

        $.each(content, function(index, icon) {
            if (~icon.styles.indexOf('brands')) {
                icons.push('fa-brands fa-' + index);
            } else {
                if (~icon.styles.indexOf('solid')) {
                    icons.push('fa-solid fa-' + index);
                }
                if (~icon.styles.indexOf('regular')) {
                    icons.push('fa-regular fa-' + index);
                }
            }
        })

        let div;
        for (let x = 0; x < icons.length;) {
            let y;
            div = '<div class="row">';
            for (y = x; y < x + 6; y++) {
                div += '<div class="icon col-lg-2"><i id="' + icons[y] + '" class="faicon ' + icons[y] + ' fa-2x"></i></div>';
            }
            div += '</div>';
            x = y;

            $("#symbolDialog .modal-content .modal-body").append(div);
        }

        $(".faicon").click(function () {
            $("#profileFieldIcon").val($(this).closest("i").attr('id'));
            $("#chosensymbol").attr("class", $(this).closest("i").attr('id'));
            $("#symbolDialog").modal('hide')
        });

        $("#noIcon").click(function () {
            $("#profileFieldIcon").val("");
        });
    });

    $(document).ready(function() {
        $(document).on('click', '.btn-add', function() {
            let $currentGroup = $(this).closest('.input-group');
            let $newGroup = $currentGroup.clone();

            $newGroup.find('input').val('');

            $currentGroup.after($newGroup);

            updateRemoveButtons();
        });

        $(document).on('click', '.btn-remove', function() {
            let $currentGroup = $(this).closest('.input-group');

            $currentGroup.remove();

            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            let $replyContainers = $('#reply-container .input-group');
            if ($replyContainers.length > 1) {
                $replyContainers.find('.btn-remove').show();
            } else {
                $replyContainers.find('.btn-remove').hide();
            }
        }

        updateRemoveButtons();
    });

    $('[name="profileField[showOnRegistration]"').click(function () {
        let showOnRegistrationRequired = $('#showOnRegistrationRequired');
        if ($(this).val() === "1") {
            showOnRegistrationRequired.removeAttr('hidden');
        } else {
            showOnRegistrationRequired.attr('hidden', '');
        }
        $('#showOnRegistrationRequired-yes').removeAttr('checked');
        $('#showOnRegistrationRequired-no').attr('checked');
    });
</script>
