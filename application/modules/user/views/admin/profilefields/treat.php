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

function addIndex(index) {
    indexList.push(index);
}
</script>

<h1>
    <?=($profileField->getId()) ? $this->getTrans('editProfileField') : $this->getTrans('addProfileField') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
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
            <?=$this->getTrans('profileFieldIcon') ?>
        </label>
        <div class="col-lg-2">
            <select class="form-control fontawesome-select" id="profileFieldIcon" name="profileField[icon]">
                <option value="" <?php if ($profileField->getIcon() == '') { echo 'selected="selected"'; } ?> disabled><?=$this->getTrans('pleaseSelect') ?></option>
                <option value="fa-globe" <?php if ($profileField->getIcon() == 'fa-globe') { echo 'selected="selected"'; } ?>>&#xf0ac; fa-globe</option>
                <option value="fa-facebook" <?php if ($profileField->getIcon() == 'fa-facebook') { echo 'selected="selected"'; } ?>>&#xf09a; fa-facebook</option>
                <option value="fa-twitter" <?php if ($profileField->getIcon() == 'fa-twitter') { echo 'selected="selected"'; } ?>>&#xf099; fa-twitter</option>
                <option value="fa-google-plus" <?php if ($profileField->getIcon() == 'fa-google-plus') { echo 'selected="selected"'; } ?>>&#xf0d5; fa-google-plus</option>
                <option value="fa-steam-square" <?php if ($profileField->getIcon() == 'fa-steam-square') { echo 'selected="selected"'; } ?>>&#xf1b7; fa-steam-square</option>
                <option value="fa-twitch" <?php if ($profileField->getIcon() == 'fa-twitch') { echo 'selected="selected"'; } ?>>&#xf1e8; fa-twitch</option>
                <option value="fa-headphones" <?php if ($profileField->getIcon() == 'fa-headphones') { echo 'selected="selected"'; } ?>>&#xf025; fa-headphones</option>
                <option value="fa-microphone" <?php if ($profileField->getIcon() == 'fa-microphone') { echo 'selected="selected"'; } ?>>&#xf130; fa-microphone</option>
            </select>
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
                <label for="profileFieldName"
                       class="control-label">
                    <?=(isset($localeList[$profileFieldTranslation->getLocale()])) ? $localeList[$profileFieldTranslation->getLocale()] : $profileFieldTranslation->getLocale() ?>
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
                       id="profileFieldName"
                       name="profileFieldTrans<?=$i ?>[name]"
                       placeholder="<?=$this->getTrans('profileFieldName') ?>"
                       value="<?=$this->escape($profileFieldTranslation->getName()) ?>" />
            </div>
            <script>addIndex(<?=$i ?>)</script>
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

<?=$this->getDialog("infoModal", $this->getTrans('info'), $this->getTrans('profileFieldTransInfoText')); ?>

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
                               'id="profileFieldName"'+
                               'name="profileFieldTrans'+index+'[name]"'+
                               'placeholder="<?=$this->getTrans('profileFieldName') ?>"'+
                               'value="" />'+
                    '</div>'+
                '</div>';
    var d1 = document.getElementById('addTranslations');
    d1.insertAdjacentHTML('beforeend', html);
    addIndex(index);
    index++;
}

function isDuplicate() {
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
            if(select_id.options[select_id.selectedIndex].value != "" && select_id.options[select_id.selectedIndex].value == allElements.value) {
                alert('<?=$this->getTrans('translationAlreadyExisting') ?>');
                deleteTranslation(indexList[y]);
                // Delete the locale so this one gets discarded.
                document.getElementsByName('profileFieldTrans'+indexList[y]+'[locale]')[0].value = '';
                indexList.splice(y,1);
                return true;
            }
        }
    }
    return false;
}

function deleteTranslation(a) {
    document.getElementById('profileFieldTrans'+a).style.display = 'none';
    document.getElementsByName('profileFieldTrans'+a+'[name]')[0].value = '';    
}
</script>
