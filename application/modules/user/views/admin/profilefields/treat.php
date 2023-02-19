<?php
$countOfProfileFields = $this->get('countOfProfileFields');
$profileField = $this->get('profileField');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$localeList = $this->get('localeList');

$type = [
    0 => 'profileFieldField',
    1 => 'profileFieldCat',
    2 => 'profileFieldIcon'
]
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

    <div class="form-group">
        <label for="profileFieldType" class="col-lg-3 control-label">
            <?=$this->getTrans('profileFieldType') ?>
        </label>
        <div class="col-lg-2">
            <select class="form-control" id="profileFieldType" name="profileField[type]">
                <?php foreach ($type as $key => $value): ?>
                    <?php $selected = ''; ?>
                    <?php if ($profileField->getType() == $key): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$selected ?> value="<?=$key ?>"><?=$this->getTrans($value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=($profileField->getType() == 2) ? '' : 'hidden' ?>" id="profileFieldIcons">
        <label for="profileFieldIcon" class="col-lg-3 control-label">
            <?=$this->getTrans('profileFieldIcon') ?>:
        </label>
        <div class="col-lg-2 input-group ilch-date">
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
    <div class="form-group">
        <label for="profileFieldKey" class="col-lg-3 control-label">
            <?=$this->getTrans('profileFieldKey') ?>
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control required"
                   id="profileFieldKey"
                   name="profileField[key]"
                   value="<?=$this->escape($profileField->getKey()) ?>" />
        </div>
    </div>
    <div class="form-group <?=($profileField->getType() == 2) ? '' : 'hidden' ?>" id="profileFieldAddition">
        <label for="profileFieldLinkAddition" class="col-lg-3 control-label">
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
    <?php
    $i = 0;
    foreach ($profileFieldsTranslation as $profileFieldTranslation): ?>
        <div class="form-group" id="profileFieldTrans<?=$i ?>">
            <div class="col-lg-3">
                <button type="button" class="btn" onclick="deleteTranslation(<?=$i ?>)">-</button>
                <label for="profileFieldName<?=$i ?>"
                       class="control-label">
                    <?= $localeList[$profileFieldTranslation->getLocale()] ?? $profileFieldTranslation->getLocale() ?>
                </label>
            </div>
            <input type="hidden"
                   name="profileFieldTrans<?=$i ?>[field_id]"
                   value="<?=$profileField->getId() ?>" />
            <input type="hidden"
                   name="profileFieldTrans<?=$i ?>[locale]"
                   value="<?=$profileFieldTranslation->getLocale() ?>" />
            <div class="col-lg-4">
                <input type="text"
                       class="form-control"
                       id="profileFieldName<?=$i ?>"
                       name="profileFieldTrans<?=$i ?>[name]"
                       placeholder="<?=$this->getTrans('profileFieldName') ?>"
                       value="<?=$this->escape($profileFieldTranslation->getName()) ?>" />
            </div>
            <script>indexList.push(<?=$i ?>)</script>
        </div>
        <?php $i++;
    endforeach; ?>
    <div id="addTranslations">
    </div>
    <div class="col-lg-3">
        <button type="button" class="btn" onclick="addTranslations()">+</button>
    </div>
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
    if ($(this).val() == "2") {
        $('#profileFieldIcons, #profileFieldAddition').removeClass('hidden');
    } else {
        $('#profileFieldIcons, #profileFieldAddition').addClass('hidden');
    }
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
                deleteTranslation(indexList[y]);
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
                    '<div class="col-lg-3">'+
                        '<button type="button" class="btn" onclick="deleteTranslation('+index+')">-</button>'+
                        '<select name="profileFieldTrans'+index+'[locale]" onchange="isDuplicate()">'+
                            '<option selected="true" disabled><?=$this->getTrans('pleaseSelect') ?></option>'+
                        <?php
                        foreach ($localeList as $locale) :?>
                            '<option value="<?=key($localeList) ?>"><?=$locale ?></option>'+
                        <?php next($localeList);
                        endforeach; ?>
                        '</select>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                        '<input type="text"'+
                               'class="form-control"'+
                               'id="profileFieldName'+index+'"'+
                               'name="profileFieldTrans'+index+'[name]"'+
                               'placeholder="<?=$this->getTrans('profileFieldName') ?>"'+
                               'value="" />'+
                    '</div>'+
                '</div>';
    var d1 = document.getElementById('addTranslations');
    d1.insertAdjacentHTML('beforeend', html);
    indexList.push(index)
    index++;
}

function deleteTranslation(a) {
    document.getElementById('profileFieldTrans'+a).remove();
    indexList.splice(indexList.indexOf(a), 1);
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
</script>
