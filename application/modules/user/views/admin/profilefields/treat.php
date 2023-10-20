<?php
$countOfProfileFields = $this->get('countOfProfileFields');
$profileField = $this->get('profileField');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$localeList = $this->get('localeList');
$type = [
    0 => 'profileFieldField',
    1 => 'profileFieldCat',
    2 => 'profileFieldIcon',
    3 => 'profileFieldRadio',
    4 => 'profileFieldCheck',
    5 => 'profileFieldDrop',
    6 => 'profileFieldDate'
];
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
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>

<form action="" method="POST" class="form-horizontal" id="profileFieldForm">
    <?=$this->getTokenField() ?>
    <input type="hidden"
           name="profileField[id]"
           value="<?=$profileField->getId() ?>" />
    <input type="hidden"
           name="profileField[position]"
           value="<?=($profileField->getId()) ? $profileField->getPosition() : $countOfProfileFields ?>" />

    <!-- select profilefield -->
    <div class="row mb-3">
        <label for="profileFieldType" class="col-xl-2 control-label">
            <?=$this->getTrans('profileFieldType') ?>
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <select class="form-control" id="profileFieldType" name="profileField[type]">
                    <?php foreach ($type as $key => $value): ?>
                        <option value="<?=$key ?>"
                            <?=($profileField->getId() && $profileField->getType() == $key) ? ' selected' : '' ?>
                            <?=($profileField->getId() && $profileField->getType() != $key) ? ' disabled' : '' ?>
                            >
                            <?=$this->getTrans($value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="input-group-text typeinfo">
                    <span class="<?=($profileField->getType()!==null) ? $iconArray[$profileField->getType()] : $iconArray[0] ?>"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- field description -->
    <div class="row mb-3">
        <label for="profileFieldDescription" class="col-lg-2 control-label">
            <?=$this->getTrans('profileFieldDescription') ?>
        </label>
        <div class="col-xl-4">
            <textarea class="form-control typedesc" id="profileFieldDescription" rows="2" readonly><?=($profileField->getType()!==null) ? $this->getTrans('profileFieldTypeDesc'.$profileField->getType()) : $this->getTrans('profileFieldTypeDesc0') ?></textarea>
        </div>
    </div>

    <!-- icon selection -->
    <div class="row mb-3 <?=($profileField->getType() == 2) ? '' : 'hidden' ?>" id="profileFieldIcons">
        <?php $icon = '';
        if ($profileField->getType() == 2) {
            $icon = ($profileField->getIcon() !== '') ? $profileField->getIcon() : $this->get('post')['symbol'];
        }
        ?>
        <label for="profileFieldIcon" class="col-xl-2 control-label">
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
        <label for="profileFieldKey" class="col-xl-2 control-label">
            <?=$this->getTrans('profileFieldKey') ?>
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control required"
                       id="profileFieldKey"
                       name="profileField[key]"
                       value="<?=$this->escape($profileField->getKey()) ?>" />
                <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="<?=$this->getTrans('profileFieldKeyDesc') ?>" data-content="<?=$this->getTrans('profileFieldKeyDesc') ?>" data-original-title="Info">
                    <span class="fa-solid fa-info"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- icon addition -->
    <div class="row mb-3 <?=($profileField->getType() == 2) ? '' : 'hidden' ?>" id="profileFieldAddition">
        <label for="profileFieldLinkAddition" class="col-xl-2 control-label">
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
        <?php $i = 0; ?>
        <?php foreach ($profileFieldsTranslation as $profileFieldTranslation): ?>
        <div class="row mb-3" id="profileFieldTrans<?=$i ?>">
            <input type="hidden" name="profileFieldTrans<?=$i ?>[field_id]" value="<?=$profileField->getId() ?>" />
            <input type="hidden" name="profileFieldTrans<?=$i ?>[locale]" value="<?=$profileFieldTranslation->getLocale() ?>" />
            <label for="profileFieldName<?=$i ?>" class="col-lg-2 control-label">
                <?=$this->getTrans('profileFieldName') ?>
            </label>
            <div class="col-xl-4">
                <div class="input-group">
                    <select class="form-control input-group-addon" name="profileFieldTrans<?=$i ?>[locale]" id="profileFieldName<?=$i ?>" onchange="isDuplicate()">
                        <option selected="selected" disabled><?=$this->getTrans('pleaseSelect') ?></option>
                        <?php foreach ($localeList as $key => $locale) :?>
                            <option value="<?=$key ?>"
                            <?=(($locale == $localeList[$profileFieldTranslation->getLocale()]) ? ' selected' : ''); ?>
                            ><?=$locale ?></option>
                        <?php next($localeList);
                        endforeach; ?>
                    </select>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-outline-secondary" onclick="deleteTranslation(<?=$i ?>)">-</button>
                    </span>
                    <input type="text"
                           class="form-control"
                           id="profileFieldName<?=$i ?>"
                           name="profileFieldTrans<?=$i ?>[name]"
                           placeholder="<?=$this->getTrans('profileFieldName') ?>"
                           value="<?=$this->escape($profileFieldTranslation->getName()) ?>" />
                </div>
            </div>
            <script>indexList.push(<?=$i ?>)</script>
            <?php $i++; ?>
        </div>
        <?php endforeach; ?>
        <div id="addTranslations"></div>
        <div class="row mb-3">
            <label for="profileFieldTranslation" class="col-xl-2 control-label">
                <?=$this->getTrans('addProfileFieldTranslation') ?>
            </label>
            <div class="col-xl-4">
                <button type="button" class="btn btn-outline-secondary" onclick="addTranslations()">+</button>
            </div>
        </div>
    </div>

    <!-- multi options -->
    <?php $multiArr = [3, 4, 5]; ?>
    <div class="profileFieldsMulti <?=(in_array($profileField->getType(), $multiArr)) ? '' : 'hidden' ?>">
        <?php if ($profileField->getOptions()) : ?>
            <?php $options = json_decode($profileField->getOptions(), true); ?>
            <div class="mb-3">
                <label for="profileFieldOptions" class="col-lg-2 control-label">
                    <?=$this->getTrans('profileFieldOptions')  ?>
                </label>
                <div class="col-xl-4">
                    <?php foreach ($options as $key => $value): ?>
                        <div class="mb-3 input-group">
                            <input type="text" name="profileFieldOptions[<?=$key ?>]" class="form-control required" value="<?=$this->escape($value) ?>" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-danger btn-remove">-</button>
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <div class="mb-3 input-group">
                        <input type="text" name="profileFieldOptions[]" class="form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-success btn-add">+</button>
                        </span>
                    </div>
                </div>
            </div>
        <?php else : ?>
        <div class="row mb-3">
            <label for="profileFieldOptions" class="col-lg-2 control-label">
                <?=$this->getTrans('profileFieldOptions') ?>
            </label>
            <div class="col-xl-4">
                <div class="mb-3 input-group">
                    <input type="text" name="profileFieldOptions[]" id="profileFieldOptions" class="form-control">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success btn-add">+</button>
                    </span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- save -->
    <?=$this->getSaveBar() ?>
</form>

<div class="modal fade" id="symbolDialog" tabindex="-1" role="dialog" aria-labelledby="symbolDialogTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="symbolDialogTitle"><?=$this->getTrans('chooseIcon') ?></h5>
                <button type="button" class="btn" id="noIcon" data-bs-dismiss="modal"><?=$this->getTrans('noIcon') ?></button>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
    let index = <?=$i ?>;
    $('#profileFieldForm').validate();

$('[name="profileField[type]"]').click(function () {
    const keysArr = ['3', '4', '5'];
    const thisKey = $(this).val();
    if (thisKey == "2") {
        $('#profileFieldIcons, #profileFieldAddition').removeClass('hidden');
    } else {
        $('#profileFieldIcons, #profileFieldAddition').addClass('hidden');
    }
    if (jQuery.inArray(thisKey, keysArr) !== -1) {
        $('.profileFieldsSingle').addClass('hidden');
        $('.profileFieldsMulti').removeClass('hidden');
    } else {
        $('.profileFieldsSingle').removeClass('hidden');
        $('.profileFieldsMulti').addClass('hidden');
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

function isDuplicate(test) {
    let allElements;
    let select_id;

    // indexList is undefined after deleting the last element with array.splice().
    if (indexList == undefined) {
        indexList = [];
    }

    for(let x = 0; x < indexList.length; x++) {
        allElements = document.getElementsByName('profileFieldTrans'+indexList[x]+'[locale]')[0];
        for(let y = x+1; y < indexList.length; y++) {
            select_id = document.getElementsByName('profileFieldTrans'+indexList[y]+'[locale]')[0];

            if (select_id.options && select_id.options[select_id.selectedIndex].value != "" && select_id.options[select_id.selectedIndex].value == allElements.value) {
                alert('<?=$this->getTrans('translationAlreadyExisting') ?>');
                deleteTranslation(indexList[y],1);
                return true;
            }
        }
    }

    return false;
}

function addTranslations() {
    if (isDuplicate()) {
        return;
    }

    const html = '<div class="row mb-3" id="profileFieldTrans' + index + '">' +
        '<input type="hidden"' +
        'name="profileFieldTrans' + index + '[field_id]"' +
        'value="<?=$profileField->getId() ?>" />' +
        '<label for="" class="col-lg-2 control-label"><?=$this->getTrans('profileFieldName') ?></label>' +
        '<div class="col-lg-4">' +
        '<div class="input-group">' +
        '<select class="form-control input-group-text" name="profileFieldTrans' + index + '[locale]" onchange="isDuplicate()" required>' +
        '<option selected="true" disabled><?=$this->getTrans('pleaseSelect') ?></option>' +
        <?php
        foreach ($localeList as $key => $locale) :?>
        '<option value="<?=$key ?>"><?=$locale ?></option>' +
        <?php next($localeList);
        endforeach; ?>
        '</select>' +
        '<span class="input-group-btn">' +
        '<button type="button" class="btn btn-outline-secondary" onclick="deleteTranslation(' + index + ')">-</button>' +
        '</span>' +
        '<input type="text"' +
        'class="form-control"' +
        'id="profileFieldName' + index + '"' +
        'name="profileFieldTrans' + index + '[name]"' +
        'placeholder="<?=$this->getTrans('profileFieldName') ?>"' +
        'value="" />' +
        '</div>' +
        '</div>' +
        '</div>';
    const d1 = document.getElementById('addTranslations');
    d1.insertAdjacentHTML('beforeend', html);
    indexList.push(index)
    index++;
}

function deleteTranslation(a, x=0) {
    if (x == 1) {
        document.getElementById('profileFieldTrans'+a).remove();
        indexList.splice(indexList.indexOf(a), 1);
    } else {
        document.getElementById('profileFieldName'+a).value = '';
    }
}

$("#symbolDialog").on('shown.bs.modal', function (e) {
    let content = JSON.parse(<?=json_encode(file_get_contents(ROOT_PATH.'/vendor/fortawesome/font-awesome/metadata/icons.json')) ?>);
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

    $(".faicon").click(function (e) {
        $("#profileFieldIcon").val($(this).closest("i").attr('id'));
        $("#chosensymbol").attr("class", $(this).closest("i").attr('id'));
        $("#symbolDialog").modal('hide')
    });

    $("#noIcon").click(function (e) {
        $("#profileFieldIcon").val("");
    });
});

(function ($) {
    $(function () {
        const countFormGroup = function ($form) {
            return $form.find('.mb-3').length;
        };
        const addFormGroup = function (event) {
            event.preventDefault();

            const $formGroup = $(this).closest('.mb-3');
            const $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            const $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-success btn-add btn-danger btn-remove')
                .html('–');

            $formGroupClone.find('input').val('');
            $formGroupClone.insertAfter($formGroup);

            const $lastFormGroupLast = $multipleFormGroup.find('.mb-3:last');
            if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', true);
            }
        };

        const removeFormGroup = function (event) {
            event.preventDefault();

            const $formGroup = $(this).closest('.mb-3');
            const $multipleFormGroup = $formGroup.closest('.multiple-form-group');

            const $lastFormGroupLast = $multipleFormGroup.find('.mb-3:last');
            if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', false);
            }

            $formGroup.remove();
        };

        $(document).on('click', '.btn-add', addFormGroup);
        $(document).on('click', '.btn-remove', removeFormGroup);
    });

    $(function () {
        $('[data-toggle="event-popover"]').popover({
            container: 'body',
            trigger: 'hover',
            placement: 'top',
        });
    });

})(jQuery);

</script>
