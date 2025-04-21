<?php

/** @var \Modules\Shop\Models\Property $property */
$property = $this->get('property');

/** @var \Modules\Shop\Models\Propertytranslation[] $propertyTranslations */
$propertyTranslations = $this->get('translations');

/** @var \Modules\Shop\Models\Propertyvalue[] $propertyValues */
$propertyValues = $this->get('values');

/** @var \Modules\Shop\Models\Propertyvaluetranslation[] $propertyValueTranslations */
$propertyValueTranslations = $this->get('valueTranslations');

/** @var string[] $localeList */
$localeList = $this->get('localeList');
?>

<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=(!empty($this->get('property'))) ? $this->getTrans('edit') : $this->getTrans('add'); ?></h1>

<form id="propertyForm" method="POST" action="">
    <?=$this->getTokenField() ?>

    <div class="row mb-3<?=$this->validation()->hasError('name') ? ' has-error' : '' ?>">
        <label for="name" class="col-xl-2 col-form-label">
            <?=$this->getTrans('propertyName') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?=($property != '') ? $this->escape($property->getName()) : $this->escape($this->originalInput('name')) ?>" />
        </div>
    </div>

    <div id="translations">
        <div class="row mb-3" id="propertyTrans">
            <label for="propertyTrans_name" class="col-lg-2 col-form-label">
                <?=$this->getTrans('propertyNameTranslations') ?>
            </label>
            <div id="trans-container" class="col-xl-4">
                <?php foreach ($propertyTranslations as $propertyTranslation) : ?>
                    <div class="mb-3 input-group">
                        <input type="hidden" name="propertyTrans_id[]" value="<?=$propertyTranslation->getId() ?>" />
                        <input type="hidden" name="propertyTrans_property_id[]" value="<?=$property ? $property->getId() : '' ?>" />

                        <select class="form-select selectTrans" name="propertyTrans_locale[]">
                            <option selected="selected" disabled><?=$this->getTrans('pleaseSelect') ?></option>
                            <?php foreach ($localeList as $key => $locale) : ?>
                                <option value="<?=$key ?>"<?=((isset($localeList[$propertyTranslation->getLocale()]) && $locale == $localeList[$propertyTranslation->getLocale()]) ? ' selected' : ''); ?>><?=$locale ?></option>
                                <?php next($localeList);
                            endforeach; ?>
                        </select>
                        <input type="text"
                               class="form-control"
                               name="propertyTrans_text[]"
                               placeholder="<?=$this->getTrans('propertyText') ?>"
                               value="<?=$this->escape($propertyTranslation->getText()) ?>" />
                        <button type="button" class="btn btn-outline-success btn-addTrans"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" class="btn btn-outline-secondary btn-removeTrans"><i class="fa-solid fa-minus"></i></button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <h1><?= $this->getTrans('propertyValues') ?></h1>
    <div id="divPropertyValue" class="row mb-3">
        <label class="col-xl-2 col-form-label" for="propertyValue">
            <?= $this->getTrans('propertyValue') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="propertyValue"
                       name="propertyValue" />
                <button class="btn btn-outline-secondary" type="button" id="addValue"><?= $this->getTrans('propertyAddValue') ?></button>
            </div>
            <div id="propertyValueHelp" class="form-text"><?= $this->getTrans('propertyValueHelp') ?></div>
        </div>
    </div>
    <div id="propertyValues" class="row mb-3<?=$this->validation()->hasError('values') ? ' has-error' : '' ?>">
        <label class="col-xl-2 col-form-label">
            <?= $this->getTrans('propertyValues') ?>:
        </label>
        <div class="col-xl-4">
            <ul class="list-group propertyValuesList" id="sortable">
                <?php foreach ($propertyValues as $id => $propertyValue) : ?>
                    <li class="list-group-item" id="value-<?= $id ?>" data-id="<?= $id ?>" data-value="<?= $this->escape($propertyValue->getValue()) ?>"><?= $this->escape($propertyValue->getValue()) ?>
                        <i class="delete text-danger float-end fa-regular fa-fw fa-trash-can"></i>
                        <i class="edit text-success float-end fa-solid fa-fw fa-pen-to-square"></i>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
        </div>
    </div>

    <div id="divHiddenInputsValues">
        <?php foreach ($propertyValues as $id => $propertyValue) : ?>
            <input type="hidden" class="values" name="values[]" id="input-value-<?= $id ?>" value="<?= $propertyValue->getValue() ?>" />
            <input type="hidden" class="values" name="values-ids[]" id="input-id-value-<?= $id ?>" value="<?= $id ?>" />
        <?php endforeach; ?>
    </div>

    <div id="divPropertyEditValue" class="row mb-3" hidden>
        <label class="col-xl-2 col-form-label" for="propertyValue">
            <?= $this->getTrans('propertyValue') ?>:
        </label>
        <div class="col-xl-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="editPropertyValue"
                       name="editPropertyValue" />
            </div>
            <div id="propertyValueHelp" class="form-text"><?= $this->getTrans('propertyValueHelp') ?></div>
        </div>
    </div>
    <div id="valueTranslations" hidden>
        <div class="row mb-3" id="propertyValueTrans">
            <label for="propertyValueTrans_name" class="col-lg-2 col-form-label">
                <?=$this->getTrans('propertyNameTranslations') ?>
            </label>
            <div id="valueTranslationsContainer" class="col-xl-4">
                <?php foreach ($propertyValueTranslations as $propertyValueTranslation) : ?>
                    <div class="mb-3 input-group propertyValueTrans" data-value-id="<?=$propertyValueTranslation->getValueId() ?>" hidden>
                        <input type="hidden" name="propertyValueTrans_id[]" value="<?=$propertyValueTranslation->getId() ?>" />
                        <input type="hidden" name="propertyValueTrans_value_id[]" value="<?=$propertyValueTranslation->getValueId() ?>" />

                        <select class="form-select selectTrans" name="propertyValueTrans_locale[]">
                            <option selected="selected" disabled><?=$this->getTrans('pleaseSelect') ?></option>
                            <?php foreach ($localeList as $key => $locale) : ?>
                                <option value="<?=$key ?>"<?=((isset($localeList[$propertyValueTranslation->getLocale()]) && $locale == $localeList[$propertyValueTranslation->getLocale()]) ? ' selected' : ''); ?>><?=$locale ?></option>
                                <?php next($localeList);
                            endforeach; ?>
                        </select>
                        <input type="text"
                               class="form-control"
                               name="propertyValueTrans_text[]"
                               placeholder="<?=$this->getTrans('propertyText') ?>"
                               value="<?=$this->escape($propertyValueTranslation->getText()) ?>" />
                        <button type="button" class="btn btn-outline-success btn-addTrans"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" class="btn btn-outline-secondary btn-removeTrans"><i class="fa-solid fa-minus"></i></button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?=(!empty($property)) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton'); ?>
</form>

<div id="valueTranslationInputsTemplate" hidden>
    <div class="mb-3 input-group propertyValueTrans" data-value-id="" hidden>
        <input type="hidden" name="propertyValueTrans_id[]" value="" />
        <input type="hidden" name="propertyValueTrans_value_id[]" class="propertyValueTrans_value_id" value="" />

        <select class="form-select selectTrans" name="propertyValueTrans_locale[]">
            <option selected="selected" disabled><?=$this->getTrans('pleaseSelect') ?></option>
            <?php foreach ($localeList as $key => $locale) : ?>
                <option value="<?=$key ?>"><?=$locale ?></option>
                <?php next($localeList);
            endforeach; ?>
        </select>
        <input type="text"
               class="form-control"
               name="propertyValueTrans_text[]"
               placeholder="<?=$this->getTrans('propertyText') ?>"
               value="" />
        <button type="button" class="btn btn-outline-success btn-addTrans"><i class="fa-solid fa-plus"></i></button>
        <button type="button" class="btn btn-outline-secondary btn-removeTrans"><i class="fa-solid fa-minus"></i></button>
    </div>
</div>


<script>
    $(function() {
        let sortableSelector = $('#sortable');

        sortableSelector.sortable({
            opacity: .75,
            placeholder: 'placeholder',
            helper: function(e, ul) {
                const $originals = ul.children();
                const $helper = ul.clone();
                $helper.children().each(function(index) {
                    $(this).width($originals.eq(index).width()+16);
                });
                return $helper;
            },
            update: function () {
                $('.sortbtn').addClass('save_button');
            }
        });
        sortableSelector.disableSelection();
    });
    $('#propertyForm').submit (
        function () {
            let items = $("#sortable li");
            let propertyValuesIDs = [items.length];
            let index = 0;
            items.each(
                function() {
                    propertyValuesIDs[index] = $(this).data('id');
                    index++;
                });
            $('#hiddenMenu').val(propertyValuesIDs.join(","));
        }
    );

    $(document).ready(function() {
        $(document).on('click', '.btn-addTrans', function() {
            let currentGroup = $(this).closest('.input-group');
            let newGroup = currentGroup.clone();

            if (!newGroup.hasClass('propertyValueTrans')) {
                newGroup.find('hidden').val('');
                newGroup.find('input').val('');
            }

            newGroup.find('select').prop('selectedIndex', 0);

            currentGroup.after(newGroup);

            updateRemoveTransButtons();

            attachChangeEvent();
        });

        function attachChangeEvent() {
            $('.selectTrans').off('change').on('change', function () {
                let selectedValue = $(this).val();
                let isDuplicate = false;

                $(".selectTrans[name='" + this.name + "']:visible").not(this).each(function () {
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
            let currentGroup = $(this).closest('.input-group');

            currentGroup.remove();

            updateRemoveTransButtons();
        });

        function updateRemoveTransButtons() {
            let replyContainers = $('#trans-container .input-group');
            if (replyContainers.length > 1) {
                replyContainers.find('.btn-removeTrans').show();
            } else {
                replyContainers.find('.btn-removeTrans').hide();
            }
        }

        updateRemoveTransButtons();

        attachChangeEvent();
    });

    document.getElementById("addValue").addEventListener("click", () => {
        let propertyValueInput = document.getElementById("propertyValue");

        if (propertyValueInput.value !== "") {
            let value = propertyValueInput.value;
            let listOfValues = document.getElementsByClassName("values");

            for (let index = 0; index < listOfValues.length; index++) {
                if (listOfValues[index].value === value) {
                    alert("<?= $this->getTrans('propertyValueAlreadyAdded') ?>");
                    return;
                }
            }

            let randomIdPart = Math.random().toString(16).slice(2);
            let propertyValuesList = document.getElementsByClassName("propertyValuesList")[0];
            let listElementValue = document.createElement("li");
            let deleteValueButton = document.createElement("i");
            let divHiddenInputsValues = document.getElementById("divHiddenInputsValues");
            let hiddenInputValues = document.createElement("input");

            listElementValue.className = "list-group-item list-group-item-info";
            listElementValue.setAttribute("id", "value-" + randomIdPart);
            listElementValue.append(value);
            deleteValueButton.setAttribute("class", "delete text-danger float-end fa-regular fa-trash-can");
            deleteValueButton.addEventListener("click", () => deleteValue(deleteValueButton));
            listElementValue.appendChild(deleteValueButton);
            propertyValuesList.prepend(listElementValue);

            hiddenInputValues.type = "hidden";
            hiddenInputValues.className = "values";
            hiddenInputValues.name = "values[]";
            hiddenInputValues.id = "input-value-" + randomIdPart;
            hiddenInputValues.value = value;
            divHiddenInputsValues.appendChild(hiddenInputValues);

            propertyValueInput.value = '';
        }
    });

    document.getElementById("editPropertyValue").addEventListener('input', function() {
        document.getElementById('input-value-' + this.dataset.id).value = this.value;
    });

    let deleteValueButtons = document.getElementsByClassName("delete");

    Array.from(deleteValueButtons).forEach(function(deleteValueButton) {
        deleteValueButton.addEventListener("click", () => deleteValue(deleteValueButton));
    });

    function deleteValue(deleteValueButton)
    {
        document.getElementById("input-" + deleteValueButton.closest("li").id).remove();
        document.getElementById("input-id-" + deleteValueButton.closest("li").id).remove();
        deleteValueButton.closest("li").remove();
    }

    let editValueButtons = document.getElementsByClassName("edit");

    Array.from(editValueButtons).forEach(function(editValueButton) {
        editValueButton.addEventListener("click", () => editValue(editValueButton));
    });

    function editValue(editValueButton)
    {
        let divTranslationsElement = document.getElementById("valueTranslations");
        let divPropertyValueInput = document.getElementById("divPropertyEditValue");
        let propertyValueInput = document.getElementById("editPropertyValue");
        let id = editValueButton.closest("li").dataset.id;
        let propertyValueTransExists = false;

        for (let item of divTranslationsElement.getElementsByClassName('propertyValueTrans')) {
            if (item.dataset.valueId === id) {
                propertyValueTransExists = true;
                break;
            }
        }

        if (!propertyValueTransExists) {
            addValueTranslationInputs(id);
        }

        for (let item of document.getElementsByClassName('propertyValueTrans')) {
            if ((item.dataset.valueId === id) || (item.dataset.valueId === "")) {
                item.removeAttribute("hidden");
            } else {
                item.setAttribute("hidden", "");
            }
        }

        propertyValueInput.value = editValueButton.closest("li").dataset.value;
        propertyValueInput.setAttribute("data-id", id);
        divTranslationsElement.removeAttribute('hidden');
        divPropertyValueInput.removeAttribute('hidden');
    }

    function addValueTranslationInputs(valueId)
    {
        let valueTranslationsContainer = document.getElementById("valueTranslationsContainer");
        let valueTranslationInputsTemplate = document.getElementById("valueTranslationInputsTemplate");
        let propertyValueTrans = valueTranslationInputsTemplate.getElementsByClassName("propertyValueTrans").item(0);
        let clone = propertyValueTrans.cloneNode(true);

        valueTranslationsContainer.appendChild(clone);
        for (let item of valueTranslationsContainer.getElementsByClassName("propertyValueTrans")) {
            if (item.dataset.valueId === "") {
                item.dataset.valueId = valueId;

                for (let item of valueTranslationsContainer.getElementsByClassName("propertyValueTrans_value_id")) {
                    if (item.value === "") {
                        item.value = valueId;
                    }
                }
            }
        }
    }
</script>
