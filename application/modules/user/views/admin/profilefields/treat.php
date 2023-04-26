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
    0 => 'fa-regular fa-square',
    1 => 'fa fa-list',
    2 => 'fa-regular fa-face-smile',
    3 => 'fa-regular fa-circle-check',
    4 => 'fa-regular fa-square-check',
    5 => 'far fa-caret-square-down',
    6 => 'fa-regular fa-calendar-days'
];
?>

<link href="<?=$this->getModuleUrl('static/css/profile-fields.css') ?>" rel="stylesheet">
<script>
var indexList = [];
</script>

<h1>
    <?=($profileField->getId()) ? $this->getTrans('editProfileField') : $this->getTrans('addProfileField') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
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
    <div class="form-group">
        <label for="profileFieldType" class="col-lg-2 control-label">
            <?=$this->getTrans('profileFieldType') ?>
        </label>
        <div class="col-lg-4">
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
                <span class="input-group-addon typeinfo">
                    <span class="<?=($profileField->getType()!==null) ? $iconArray[$profileField->getType()] : $iconArray[0] ?>"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- field description -->
    <div class="form-group">
        <label for="profileFieldKey" class="col-lg-2 control-label">
            <?=$this->getTrans('profileFieldDescription') ?>
        </label>
        <div class="col-lg-4">
            <textarea class="form-control typedesc" rows="2" readonly /><?=($profileField->getType()!==null) ? $this->getTrans('profileFieldTypeDesc'.$profileField->getType()) : $this->getTrans('profileFieldTypeDesc0') ?></textarea>
        </div>
    </div>
    
    <!-- icon selection -->
    <div class="form-group <?=($profileField->getType() == 2) ? '' : 'hidden' ?>" id="profileFieldIcons">
        <label for="profileFieldIcon" class="col-lg-2 control-label">
            <?=$this->getTrans('profileFieldIcon') ?>:
        </label>
        <div class="col-lg-4 input-group ilch-date">
            <span class="input-group-addon">
                <span id="chosensymbol" class="<?=($profileField->getIcon() !== '') ? $profileField->getIcon() : $this->get('post')['symbol'] ?>"></span>
            </span>
            <input type="text"
                   class="form-control"
                   id="profileFieldIcon"
                   name="profileField[icon]"
                   value="<?=($profileField->getIcon() !== '') ? $profileField->getIcon() : $this->get('post')['symbol'] ?>"
                   readonly />
            <span class="input-group-addon">
                <span class="fa-solid fa-arrow-pointer" data-toggle="modal" data-target="#symbolDialog"></span>
            </span>
        </div>
    </div>

    <!-- db key -->
    <div class="form-group">
        <label for="profileFieldKey" class="col-lg-2 control-label">
            <?=$this->getTrans('profileFieldKey') ?>
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control required"
                       id="profileFieldKey"
                       name="profileField[key]"
                       value="<?=$this->escape($profileField->getKey()) ?>" />
                <span class="input-group-addon" data-toggle="event-popover" title="" data-content="<?=$this->getTrans('profileFieldKeyDesc') ?>" data-original-title="Info">
                    <span class="fa-solid fa-info"></span>
                </span>
            </div>
        </div>
    </div>
    
    <!-- icon addition -->
    <div class="form-group <?=($profileField->getType() == 2) ? '' : 'hidden' ?>" id="profileFieldAddition">
        <label for="profileFieldLinkAddition" class="col-lg-2 control-label">
            <?=$this->getTrans('profileFieldLinkAddition') ?>
        </label>
        <div class="col-lg-4">
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
        <div class="form-group" id="profileFieldTrans<?=$i ?>">
            <input type="hidden" name="profileFieldTrans<?=$i ?>[field_id]" value="<?=$profileField->getId() ?>" />
            <input type="hidden" name="profileFieldTrans<?=$i ?>[locale]" value="<?=$profileFieldTranslation->getLocale() ?>" />
            <label for="profileFieldName<?=$i ?>" class="col-lg-2 control-label">
                <?=$this->getTrans('profileFieldName') ?>
            </label>
            <div class="col-lg-4">
                <div class="input-group">
                    <select class="form-control input-group-addon" name="profileFieldTrans<?=$i ?>[locale]" onchange="isDuplicate()">
                        <option selected="true" disabled><?=$this->getTrans('pleaseSelect') ?></option>
                        <?php foreach ($localeList as $key => $locale) :?>
                            <option value="<?=$key ?>" 
                            <?=(($locale == $localeList[$profileFieldTranslation->getLocale()]) ? ' selected' : ''); ?>
                            ><?=$locale ?></option>
                        <?php next($localeList);
                        endforeach; ?>
                    </select>
                    <span class="input-group-btn">
                        <button type="button" class="btn" onclick="deleteTranslation(<?=$i ?>)">-</button>
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
        <div class="form-group">
            <label for="profileFieldTranslation" class="col-lg-2 control-label">
                <?=$this->getTrans('addProfileFieldTranslation') ?>
            </label>
            <div class="col-lg-4">
                <button type="button" class="btn" onclick="addTranslations()">+</button>
            </div>
        </div>
    </div>

    <!-- multi options -->
    <?php $multiArr = [3, 4, 5]; ?>
    <div class="profileFieldsMulti <?=(in_array($profileField->getType(), $multiArr)) ? '' : 'hidden' ?>">
        <?php if ($profileField->getOptions()) { 
            $options = unserialize($profileField->getOptions());
            $optionsAnz = 1; ?>
            <div class="form-group">
                <label for="profileFieldOptions" class="col-lg-2 control-label">
                    <?=($optionsAnz == 1) ? $this->getTrans('profileFieldOptions') : ''; $optionsAnz++; ?>
                </label>
                <div class="col-lg-4">
                    <?php foreach ($options as $key => $value): ?>
                        <div class="form-group input-group">
                            <input type="text" name="profileFieldOptions[<?=$key ?>]" class="form-control required" value="<?=$this->escape($value) ?>" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-danger btn-remove">-</button>
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <div class="form-group input-group">
                        <input type="text" name="profileFieldOptions[]" class="form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-success btn-add">+</button>
                        </span>
                    </div>
                </div>    
            </div>
        <?php } else { ?>
        <div class="form-group">
            <label for="profileFieldOptions" class="col-lg-2 control-label">
                <?=$this->getTrans('profileFieldOptions') ?>
            </label>
            <div class="col-lg-4">
                <div class="form-group input-group">
                    <input type="text" name="profileFieldOptions[]" class="form-control">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success btn-add">+</button>
                    </span>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- save -->
    <?=$this->getSaveBar() ?>
</form>

<div class="modal fade" id="symbolDialog" tabindex="-1" role="dialog" aria-labelledby="symbolDialogTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="symbolDialogTitle"><?=$this->getTrans('chooseIcon') ?></h5>
                <button type="button" class="btn" id="noIcon" data-dismiss="modal"><?=$this->getTrans('noIcon') ?></button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$this->getTrans('close') ?></button>
            </div>
        </div>
    </div>
</div>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('profileFieldTransInfoText')) ?>

<script>
var index = <?=$i ?>;
$('#profileFieldForm').validate();

$('[name="profileField[type]"]').click(function () {
    var keysArr = ['3','4','5'];
    var thisKey = $(this).val();
    if (thisKey == "2") {
        $('#profileFieldIcons, #profileFieldAddition').removeClass('hidden');
    } else {
        $('#profileFieldIcons, #profileFieldAddition').addClass('hidden');
    };
    if (jQuery.inArray(thisKey, keysArr) !== -1) {
        $('.profileFieldsSingle').addClass('hidden');
        $('.profileFieldsMulti').removeClass('hidden');
    } else {
        $('.profileFieldsSingle').removeClass('hidden');
        $('.profileFieldsMulti').addClass('hidden');
    };
});

$('select#profileFieldType').change(function() {
    var typeKey = $('#profileFieldType').find(':selected').val();
    var typeDesc0 = '<?=$this->getTrans('profileFieldTypeDesc0') ?>';
    var typeDesc1 = '<?=$this->getTrans('profileFieldTypeDesc1') ?>';
    var typeDesc2 = '<?=$this->getTrans('profileFieldTypeDesc2') ?>';
    var typeDesc3 = '<?=$this->getTrans('profileFieldTypeDesc3') ?>';
    var typeDesc4 = '<?=$this->getTrans('profileFieldTypeDesc4') ?>';
    var typeDesc5 = '<?=$this->getTrans('profileFieldTypeDesc5') ?>';
    var typeDesc6 = '<?=$this->getTrans('profileFieldTypeDesc6') ?>';
    var typeDesc7 = '<?=$this->getTrans('profileFieldTypeDesc7') ?>';
    var iconArray = ['fa-regular fa-square','fa fa-list','fa-regular fa-face-smile','fa-regular fa-circle-check','fa-regular fa-square-check','far fa-caret-square-down','fa-regular fa-calendar-days'];
    $('.typeinfo').html('<span class="'+iconArray[typeKey]+'"></span>');
    $('.typedesc').val(eval("typeDesc"+typeKey));
});

function isDuplicate(test) {
    var allElements;
    var select_id;

    // indexList is undefined after deleting the last element with array.splice().
    if (indexList == undefined) {
        indexList = [];
    }

    for(x = 0; x < indexList.length; x++) {
        allElements = document.getElementsByName('profileFieldTrans'+indexList[x]+'[locale]')[0];
        for(y = x+1; y < indexList.length; y++) {
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

    var html =  '<div class="form-group" id="profileFieldTrans'+index+'">'+
                    '<input type="hidden"'+
                        'name="profileFieldTrans'+index+'[field_id]"'+
                        'value="<?=$profileField->getId() ?>" />'+
                        '<label for="" class="col-lg-2 control-label"><?=$this->getTrans('profileFieldName') ?></label>'+
                    '<div class="col-lg-4">'+
                    '<div class="input-group">'+
                        '<select class="form-control input-group-addon" name="profileFieldTrans'+index+'[locale]" onchange="isDuplicate()" required>'+
                            '<option selected="true" disabled><?=$this->getTrans('pleaseSelect') ?></option>'+
                        <?php
                        foreach ($localeList as $key => $locale) :?>
                            '<option value="<?=$key ?>"><?=$locale ?></option>'+
                        <?php next($localeList);
                        endforeach; ?>
                        '</select>'+
                        '<span class="input-group-btn">'+
                            '<button type="button" class="btn" onclick="deleteTranslation('+index+')">-</button>'+
                        '</span>'+
                        '<input type="text"'+
                               'class="form-control"'+
                               'id="profileFieldName'+index+'"'+
                               'name="profileFieldTrans'+index+'[name]"'+
                               'placeholder="<?=$this->getTrans('profileFieldName') ?>"'+
                               'value="" />'+
                        '</div>'+
                    '</div>'+
                '</div>';
    var d1 = document.getElementById('addTranslations');
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
            icons.push('fa-brand fa-' + index);
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
    
    $(function () {
        $('[data-toggle="event-popover"]').popover({
            container: 'body',
            trigger: 'hover',
            placement: 'top',
        });
    });

})(jQuery);

</script>
