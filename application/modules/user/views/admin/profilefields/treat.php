<?php
$countOfProfileFields = $this->get('countOfProfileFields');
$profileField = $this->get('profileField');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$localeList = $this->get('localeList');

if ($profileField->getId()) {
    $fieldsetLegend = $this->getTrans('editProfileField');
} else {
    $fieldsetLegend = $this->getTrans('addProfileField');
}
?>

<script>
var indexList = [];

function addIndex(index) {
    indexList.push(index);
}
</script>

<h1>
    <?=$fieldsetLegend ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
    </a>
</h1>
<form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'profilefields', 'action' => 'save']) ?>" method="POST" class="form-horizontal" id="profileFieldForm">
    <?=$this->getTokenField() ?>
    <input type="hidden"
           name="profileField[id]"
           value="<?=$profileField->getId() ?>" />
    <div class="form-group">
        <label for="profileFieldName" class="col-lg-3 control-label">
            <?=$this->getTrans('profileFieldName') ?>
        </label>
        <div class="col-lg-9">
            <input type="text"
                   class="form-control required"
                   id="profileFieldName"
                   name="profileField[name]"
                   placeholder="<?=$this->getTrans('profileFieldName') ?>"
                   value="<?=$this->escape($profileField->getName()) ?>" />
        </div>
        <div class="col-lg-9">
            <label>
                <?php if (!$profileField->getType()) : ?>
                    <input type="checkbox" name="profileField[type]" value="1" /> <?=$this->getTrans('cat') ?>
                <?php else : ?>
                    <input type="checkbox" name="profileField[type]" value="1" checked="checked" /> <?=$this->getTrans('cat') ?>
                <?php endif; ?>
            </label>
            <input type="hidden"
                   name="profileField[position]"
                   value="<?=($profileField->getId()) ? $profileField->getPosition() : $countOfProfileFields ?>" />
        </div>
    </div>
    <?php
    $i = 0;
    foreach ($profileFieldsTranslation as $profileFieldTranslation) : ?>
    <div class="form-group" id="profileFieldTrans<?=$i ?>">
        <div class="col-lg-3">
            <button type="button" class="btn" onclick="deleteTranslation(<?=$i ?>)">-</button>
            <label for="profileFieldName"
                   class="control-label">
                <?=(isset($localeList[$profileFieldTranslation->getLocale()])) ? $localeList[$profileFieldTranslation->getLocale()] : $profileFieldTranslation->getLocale() ?>
            </label>
        </div>
        <input type="hidden"
               name="profileFieldTrans<?=$i?>[field_id]"
               value="<?=$profileField->getId() ?>" />
        <input type="hidden"
               name="profileFieldTrans<?=$i?>[locale]"
               value="<?=$profileFieldTranslation->getLocale() ?>" />
        <div class="col-lg-9">
            <input type="text"
                   class="form-control"
                   id="profileFieldName"
                   name="profileFieldTrans<?=$i?>[name]"
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
                            '<option value=""></option>'+
                        <?php
                        foreach ($localeList as $locale) :?>
                            '<option value="<?=key($localeList) ?>"><?=$locale ?></option>'+
                        <?php next($localeList);
                        endforeach; ?>
                        '</select>'+
                    '</div>'+
                    '<div class="col-lg-9">'+
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
