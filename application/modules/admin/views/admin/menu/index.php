<?php
use Ilch\View;
use Modules\Admin\Mappers\Menu as MenuMapper;
use Modules\Admin\Models\MenuItem;

$menuItems = $this->get('menuItems');
$menuMapper = $this->get('menuMapper');
$pages = $this->get('pages');
$modules = $this->get('modules');
$boxes = $this->get('boxes');
$selfBoxes = $this->get('self_boxes');
$targets = [
    '_blank' => 'targetBlank',
    '_self' => 'targetSelf',
    '_parent' => 'targetParent',
    '_top' => 'targetTop',
];

function rec(MenuMapper $menuMapper, View $view) {
    $items = $menuMapper->getMenuItems($view->get('menu')->getId());

    // prepare array with parent-child relations
    $menuData = [
        'items' => [],
        'parents' => []
    ];

    foreach ($items as $item) {
        $menuData['items'][$item->getId()] = $item;
        $menuData['parents'][$item->getParentId()][] = $item->getId();
    }

    buildMenu(0, $menuData, $view);
}

function buildMenu($parentId, $menuData, View $view) {
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (isset($menuData['parents'][$parentId])) {
        foreach ($menuData['parents'][$parentId] as $itemId) {
            if (!isset($menuData['parents'][$itemId])) {
                $class = 'mjs-nestedSortable-leaf';
            }

            if ($menuData['items'][$itemId]->isBox()) {
                $class .= ' mjs-nestedSortable-no-nesting';
            }

            if ($menuData['items'][$itemId]->getBoxId() > 0) {
                $boxKey = $menuData['items'][$itemId]->getBoxId();
            } else {
                $boxKey = $menuData['items'][$itemId]->getBoxKey();
            }

            echo '<li id="list_'.$menuData['items'][$itemId]->getId().'" class="'.$class.'">';
            echo '<div>
                <span class="disclose">
                    <i class="fa-solid fa-circle-minus"></i>
                    <input type="hidden" class="hidden_id" name="items['.$menuData['items'][$itemId]->getId().'][id]" value="'.$menuData['items'][$itemId]->getId().'" />
                    <input type="hidden" class="hidden_title" name="items['.$menuData['items'][$itemId]->getId().'][title]" value="'.$view->escape($menuData['items'][$itemId]->getTitle()).'" />
                    <input type="hidden" class="hidden_href" name="items['.$menuData['items'][$itemId]->getId().'][href]" value="'.$menuData['items'][$itemId]->getHref().'" />
                    <input type="hidden" class="hidden_target" name="items['.$menuData['items'][$itemId]->getId().'][target]" value="'.$menuData['items'][$itemId]->getTarget().'" />
                    <input type="hidden" class="hidden_type" name="items['.$menuData['items'][$itemId]->getId().'][type]" value="'.$menuData['items'][$itemId]->getType().'" />
                    <input type="hidden" class="hidden_siteid" name="items['.$menuData['items'][$itemId]->getId().'][siteid]" value="'.$menuData['items'][$itemId]->getSiteId().'" />
                    <input type="hidden" class="hidden_boxkey" name="items['.$menuData['items'][$itemId]->getId().'][boxkey]" value="'.$boxKey.'" />
                    <input type="hidden" class="hidden_modulekey" name="items['.$menuData['items'][$itemId]->getId().'][modulekey]" value="'.$menuData['items'][$itemId]->getModuleKey().'" />
                    <input type="hidden" class="hidden_access" name="items['.$menuData['items'][$itemId]->getId().'][access]" value="'.$menuData['items'][$itemId]->getAccess().'" />
                    <span></span>
                </span>
                <span class="title">'.$view->escape($menuData['items'][$itemId]->getTitle()).'</span>
                <span class="item_delete"><i class="fa-solid fa-times-circle"></i></span>
                <span class="item_edit"><i class="fa-regular fa-pen-to-square"></i></span>
            </div>';

            if (isset($menuData['parents'][$itemId])) {
                echo '<ol>';
                // find childitems recursively
                buildMenu($itemId, $menuData, $view);
                echo '</ol>';
                echo '</li>';
            }
        }
    }
}
?>

<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/main.css') ?>">

<form class="form-horizontal" id="menuForm" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'menu' => $this->get('menu')->getId()]) ?>">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <?php $iMenu = 1; ?>
        <?php foreach ($this->get('menus') as $menu): ?>
            <?php $active = ''; ?>

            <?php if ($menu->getId() == $this->get('menu')->getId()): ?>
                <?php $active = 'active'; ?>
            <?php endif; ?>
            <li class="<?=$active ?> nav-item">
                <a class="nav-link <?=$active ?>" href="<?=$this->getUrl(['menu' => $menu->getId()]) ?>"><?=$this->getTrans('menu') ?> <?=$iMenu ?></a>
            </li>
            <?php $iMenu++; ?>
        <?php endforeach; ?>
        <li class="nav-item"><a class="nav-link" href="<?=$this->getUrl(['action' => 'add']) ?>">+</a></li>
    </ul>
    <br />
    <h1><?=$this->getTrans('menuChange') ?></h1>
    <div class="row mb-3">
        <div class="col-md-7 col-xl-7">
            <ol id="sortable" class="sortable">
                <?php if (!empty($menuItems)): ?>
                    <?php rec($menuMapper, $this) ?>
                <?php endif; ?>
            </ol>
        </div>
        <div class="col-md-5 col-xl-5 changeBox">
            <input type="hidden" id="id" value="" />
            <div class="row mb-3">
                <label for="title" class="col-lg-4 control-label">
                    <?=$this->getTrans('itemTitle') ?>
                </label>
                <div class="col-xl-8">
                    <input type="text" class="form-control" id="title" />
                </div>
            </div>
            <div class="row mb-3">
                <label for="type" class="col-xl-4 control-label">
                    <?=$this->getTrans('itemType') ?>
                </label>
                <div class="col-xl-8">
                    <select class="form-control" id="type">
                        <option value="<?=MenuItem::TYPE_MENU ?>"><?=$this->getTrans('menu') ?></option>
                        <option value="<?=MenuItem::TYPE_BOX ?>"><?=$this->getTrans('itemTypeBox') ?></option>
                        <optgroup label="<?=$this->getTrans('linking') ?>">
                            <option value="<?=MenuItem::TYPE_MODULE_LINK ?>"><?=$this->getTrans('moduleLinking') ?></option>
                            <option value="<?=MenuItem::TYPE_PAGE_LINK ?>"><?=$this->getTrans('siteLinking') ?></option>
                            <option value="<?=MenuItem::TYPE_LINK ?>"><?=$this->getTrans('linking') ?></option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="dyn"></div>
            <div class="row mb-3">
                <label for="access" class="col-xl-4 control-label">
                    <?=$this->getTrans('notVisible') ?>
                </label>
                <div class="col-xl-8">
                    <select class="chosen-select form-control" id="access" name="user[groups][]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                        <?php foreach ($this->get('userGroupList') as $groupList): ?>
                            <option value="<?=$groupList->getId() ?>"><?=$groupList->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="actions col-xl-12 text-right">
                <input type="button" class="btn btn-outline-secondary" id="menuItemAdd" value="<?=$this->getTrans('menuItemAdd') ?>">
            </div>
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar('saveButton', null, 'deleteMenu') ?>
</form>

<script>
function resetBox() {
    let type = $('#type');
    let access = $('#access');

    $(':input','.changeBox')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');

    type.val('0');
    type.change();
    access.val('');
    access.trigger("chosen:updated");
}

$('.deleteMenu').on('click', function() {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function() {
    window.location = $(this).data('clickurl');
});

$(document).ready
(
    function () {
        let itemId = 999;
        let sortable = $('.sortable');
        let menuForm = $('#menuForm');

        sortable.nestedSortable ({
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 8,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false,
            stop: function(event, ui) {
                let val = ui.item.find('input.hidden_type').val();

                if ((val == 4 || val == 0)) {
                    if (ui.item.closest('ol').closest('li').find('input.hidden_type:first').val() != undefined) {
                        event.preventDefault();
                    }
                } else {
                    if (ui.item.closest('ol').closest('li').find('input.hidden_type:first').val() == undefined) {
                        event.preventDefault();
                    }
                }
            }
        });

        $('.disclose').on('click', function () {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
            $(this).find('i').toggleClass('fa-minus-circle').toggleClass('fa-plus-circle');
        });

        menuForm.submit (
            function () {
                $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
            }
        );

        let entityMap = {
            "&": "",
            "<": "",
            ">": "",
            '"': '',
            "'": '',
            "/": '',
            "(": '',
            ")": '',
            ";": '',
            " ": ' '
        };

        function escapeHtml(string) {
            return String(string).replace(/[&<>"'\/(); ]/g, function (s) {
                return entityMap[s];
            });
        }

        menuForm.on('click', '#menuItemAdd', function () {
            let title = escapeHtml($('#title').val());
            let type = $('#type');

            if (title == '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                return;
            }

            let append = '#sortable';
            let menuKey = $('#menukey');

            if (type.val() != 0 && type.val() != 4 && menuKey.val() != 0) {
                let id = menuKey.val();

                if ($('#sortable #'+id+' ol').length > 0) {

                } else {
                    $('<ol></ol>').appendTo('#sortable #'+id);
                }

                if (!isNaN(id)) {
                    append = '#sortable #list_'+id+' ol';

                    if ($(append).length == 0) {
                        $('<ol></ol>').appendTo('#sortable #list_'+id);
                    }
                } else {
                    if ($(append).length == 0) {
                        $('<ol></ol>').appendTo('#sortable #'+id);
                    }
                    append = '#sortable #'+id+' ol';
                }

            }

            let modulKeyValue = $('#modulekey').val();
            let boxKeyValue = $('#boxkey').val();

            if (typeof modulKeyValue == "undefined" && typeof boxKeyValue != "undefined")
            {
                let boxkeyParts = boxKeyValue.split('_');
                modulKeyValue = boxkeyParts[0];
            }

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                    +'<input type="hidden" class="hidden_id" name="items[tmp_'+itemId+'][id]" value="tmp_'+itemId+'" />'
                    +'<input type="hidden" class="hidden_title" name="items[tmp_'+itemId+'][title]" value="'+title+'" />'
                    +'<input type="hidden" class="hidden_href" name="items[tmp_'+itemId+'][href]" value="'+$('#href').val()+'" />'
                    +'<input type="hidden" class="hidden_target" name="items[tmp_'+itemId+'][target]" value="'+$('#target').val()+'" />'
                    +'<input type="hidden" class="hidden_type" name="items[tmp_'+itemId+'][type]" value="'+type.val()+'" />'
                    +'<input type="hidden" class="hidden_siteid" name="items[tmp_'+itemId+'][siteid]" value="'+$('#siteid').val()+'" />'
                    +'<input type="hidden" class="hidden_boxkey" name="items[tmp_'+itemId+'][boxkey]" value="'+boxKeyValue+'" />'
                    +'<input type="hidden" class="hidden_modulekey" name="items[tmp_'+itemId+'][modulekey]" value="'+modulKeyValue+'" />'
                    +'<input type="hidden" class="hidden_menukey" name="items[tmp_'+itemId+'][menukey]" value="'+menuKey.val()+'" />'
                    +'<input type="hidden" class="hidden_access" name="items[tmp_'+itemId+'][access]" value="'+$('#access').val()+'" />'
                    +'</span></span><span class="title">'+title+'</span><span class="item_delete"><i class="fa-solid fa-times-circle"></i></span><span class="item_edit"><i class="fa-regular fa-pen-to-square"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
            }
        );

        menuForm.on('click', '#menuItemEdit', function () {
                let title = escapeHtml($('#title').val());

                if (title == '') {
                    alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                    return;
                }

                let modulKeyValue = $('#modulekey').val();
                let boxKeyValue = $('#boxkey').val();
                let id = $('#'+$('#id').val());

                if (typeof modulKeyValue == "undefined" && typeof boxKeyValue != "undefined")
                {
                    let boxkeyParts = boxKeyValue.split('_');
                    modulKeyValue = boxkeyParts[0];
                }

                id.find('.title:first').text(title);
                id.find('.hidden_title:first').val(title);
                id.find('.hidden_href:first').val($('#href').val());
                id.find('.hidden_target:first').val($('#target').val());
                id.find('.hidden_type:first').val($('#type').val());
                id.find('.hidden_siteid:first').val($('#siteid').val());
                id.find('.hidden_modulekey:first').val(modulKeyValue);
                id.find('.hidden_boxkey:first').val(boxKeyValue);
                id.find('.hidden_menukey:first').val($('#menukey').val());
                id.find('.hidden_access:first').val($('#access').val());
                resetBox();
            }
        );

        sortable.on('click', '.item_delete', function() {
            $(this).closest('li').remove();
        });

        menuForm.on('change', '#type', function() {
            let options = '';

            $('#sortable').find('li').each(function() {
                if ($(this).find('input.hidden_type:first').val() == 0) {
                    options += '<option value="'+$(this).find('input.hidden_id:first').val()+'">'+$(this).find('input.hidden_title:first').val()+'</option>';
                }
            });

            if (options == '' && ($(this).val() == '1' || $(this).val() == '2' || $(this).val() == '3')) {
                alert(<?=json_encode($this->getTrans('missingMenu')) ?>);
                $(this).val(0);
                return;
            }

            let menuHtml = '<div class="row mb-3"><label for="menukey" class="col-xl-4 control-label"><?=$this->getTrans('labelMenu') ?></label>\n\
                            <div class="col-xl-8"><select class="form-control" id="menukey">'+options+'</select></div></div>';

            if ($(this).val() == '0') {
                $('.dyn').html('');
            } else if ($(this).val() == '1') {
                $('.dyn').html('<div class="row mb-3"><label for="href" class="col-xl-4 control-label"><?=$this->getTrans('address') ?></label>\n\
                                <div class="col-xl-8"><input type="text" class="form-control" id="href" value="http://" /></div></div>\n\
                                <div class="row mb-3"><label for="target" class="col-xl-4 control-label"><?=$this->getTrans('target') ?></label>\n\
                                <div class="col-xl-8"><select class="form-control" id="target"><?php foreach ($targets as $target => $translation) { echo '<option value="'.$target.'">'.$this->getTrans($translation).'</option>';} ?></select></div></div>'+menuHtml);
            } else if ($(this).val() == '2') {
                 $('.dyn').html('<div class="row mb-3"><label for="siteid" class="col-xl-4 control-label"><?=$this->getTrans('page') ?></label>\n\
                                <div class="col-xl-8"><?php if (!empty($pages)) { echo '<select class="form-control" id="siteid">'; foreach ($pages as $page) { echo '<option value="'.$page->getId().'">'.$this->escape($page->getTitle()).'</option>';} echo '</select>'; } else { echo $this->getTrans('missingSite'); } ?></div></div>'+menuHtml);
            } else if ($(this).val() == '3') {
                $('.dyn').html('<div class="row mb-3"><label for="modulekey" class="col-xl-4 control-label"><?=$this->getTrans('module') ?></label>\n\
                                <div class="col-lg-8"><?php if (!empty($modules)) { echo '<select class="form-control" id="modulekey">'; foreach ($modules as $module) { if ($module->getHideMenu() != true) { $content = $module->getContentForLocale($this->getTranslator()->getLocale()); echo '<option value="'.$module->getKey().'">'.$content['name'].'</option>';}} echo '</select>'; } else { echo $this->getTrans('missingModule'); } ?></div></div>'+menuHtml);
            } else if ($(this).val() == '4') {
                $('.dyn').html('<div class="row mb-3"><label for="boxkey" class="col-xl-4 control-label"><?=$this->getTrans('box') ?></label>\n\
                                <div class="col-xl-8"><?='<select class="form-control" id="boxkey">';
                    foreach ($boxes as $box) { echo '<option value="'.$box->getModule().'_'.$box->getKey().'">'.$box->getName().'</option>'; } foreach ($selfBoxes as $box) { echo '<option value="'.$box->getId().'">self_'.$this->escape($box->getTitle()).'</option>';} echo '</select>'; ?></div></div>');
            }
        });

        menuForm.on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemAdd" value="<?=$this->getTrans('menuItemAdd') ?>">');
            resetBox();
        });

        sortable.on('click', '.item_edit', function() {
            let type = $('#type');
            let access = $('#access');

            $('.actions').html('<input type="button" class="btn" id="menuItemEdit" value="<?=$this->getTrans('edit') ?>">\n\
                                <input type="button" class="btn" id="menuItemEditCancel" value="<?=$this->getTrans('cancel') ?>">');
            $('#title').val($(this).parent().find('.hidden_title').val());
            type.val($(this).parent().find('.hidden_type').val());
            $('#id').val($(this).closest('li').attr('id'));
            type.change();
            $('#href').val($(this).parent().find('.hidden_href').val());
            $('#target').val($(this).parent().find('.hidden_target').val());
            $('#siteid').val($(this).parent().find('.hidden_siteid').val());
            $('#boxkey').val($(this).parent().find('.hidden_boxkey').val());
            $('#modulekey').val($(this).parent().find('.hidden_modulekey').val());
            $('#menukey').val($(this).parent().find('.hidden_menukey').val());
            access.val($(this).parent().find('.hidden_access').val());
            $.each($(this).parent().find('.hidden_access').val().split(","), function (index, element) {
                if (element !== "") {
                    $('#access > option[value=' + element + ']').prop("selected", true);
                }
            });
            access.trigger("chosen:updated");
        });

        $('#access').chosen();
    }
);
</script>
