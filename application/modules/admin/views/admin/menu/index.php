<?php
$menuItems = $this->get('menuItems');
$menuMapper = $this->get('menuMapper');
$pages = $this->get('pages');
$modules = $this->get('modules');
$boxes = $this->get('boxes');

function rec($item, $menuMapper, $obj) {
    $subItems = $menuMapper->getMenuItemsByParent($obj->get('menu')->getId(), $item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    if ($item->getType() == 4) {
        $class .= ' mjs-nestedSortable-no-nesting';
    }

    if ($item->getBoxId() > 0) {
        $boxKey = $item->getBoxId();
    } else {
        $boxKey = $item->getBoxKey();
    }

    echo '<li id="list_'.$item->getId().'" class="'.$class.'">';
    echo '<div><span class="disclose"><i class="fa fa-minus-circle"></i>
                    <input type="hidden" name="items['.$item->getId().'][id]" class="hidden_id" value="'.$item->getId().'" />
                    <input type="hidden" name="items['.$item->getId().'][title]" class="hidden_title" value="'.$item->getTitle().'" />
                    <input type="hidden" name="items['.$item->getId().'][href]" class="hidden_href" value="'.$item->getHref().'" />
                    <input type="hidden" name="items['.$item->getId().'][type]" class="hidden_type" value="'.$item->getType().'" />
                    <input type="hidden" name="items['.$item->getId().'][siteid]" class="hidden_siteid" value="'.$item->getSiteId().'" />
                    <input type="hidden" name="items['.$item->getId().'][boxkey]" class="hidden_boxkey" value="'.$boxKey.'" />
                    <input type="hidden" name="items['.$item->getId().'][modulekey]" class="hidden_modulekey" value="'.$item->getModuleKey().'" />
                    <span></span>
                </span><span class="title">'.$item->getTitle().'</span><span class="item_delete"><i class="fa fa-times-circle"></i></span><span class="item_edit"><i class="fa fa-edit"></i></span></div>';

    if (!empty($subItems)) {
        echo '<ol>';

        foreach ($subItems as $subItem) {
            rec($subItem, $menuMapper, $obj);
        }

        echo '</ol>';
    }

    echo '</li>';
}
?>

<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/main.css') ?>">

<form class="form-horizontal" id="menuForm" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName(), 'menu' => $this->get('menu')->getId())) ?>">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <?php $iMenu = 1; ?>
        <?php foreach ($this->get('menus') as $menu): ?>
            <?php $active = ''; ?>

            <?php if($menu->getId() == $this->get('menu')->getId()): ?>
                <?php $active = 'active'; ?>
            <?php endif; ?>
            <li class="<?=$active ?>">
                <a href="<?=$this->getUrl(array('menu' => $menu->getId())) ?>"><?=$this->getTrans('menu') ?> <?=$iMenu ?></a>
            </li>
            <?php $iMenu++; ?>
        <?php endforeach; ?>
        <li><a href="<?=$this->getUrl(array('action' => 'add')) ?>">+</a></li>
    </ul>
    <br />
    <legend><?=$this->getTrans('menuChange') ?></legend>
    <div class="form-group">
        <div class="col-lg-6">
            <ol id="sortable" class="sortable">
                <?php if (!empty($menuItems)): ?>
                    <?php foreach ($menuItems as $item): ?>
                        <?php rec($item, $menuMapper, $this); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ol>
        </div>
        <div class="col-lg-1"></div>
        <div class="col-lg-5 changeBox">
            <input type="hidden" id="id" value="" />
            <div class="form-group">
                <label for="title" class="col-lg-2 control-label">
                    Itemtitel
                </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="title" />
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-lg-2 control-label">
                    Itemtyp
                </label>
                <div class="col-lg-4">
                    <select id="type" class="form-control">
                        <option value="0">Menu</option>
                        <optgroup>
                            <option value="1">Externe Verlinkung</option>
                            <option value="2">Seiten Verlinkung</option>
                            <option value="3">Modul Verlinkung</option>
                        </optgroup>
                        <option value="4">Box</option>
                    </select>
                </div>
            </div>
            <div class="dyn"></div>
            <div class="actions">
                <input type="button" id="menuItemAdd" value="<?=$this->getTrans('menuItemAdd') ?>" class="btn">
            </div>
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar('saveButton', null, 'deleteMenu') ?>
</form>

<?php
$boxesDir = array();

foreach (glob(APPLICATION_PATH.'/modules/*') as $moduleKey) {
    $moduleKey = basename($moduleKey);
    $boxesGlob = glob(APPLICATION_PATH.'/modules/'.$moduleKey.'/boxes/*');

    if(!empty($boxesGlob)) {
        foreach ($boxesGlob as $box) {
            if (is_dir($box)) {
                continue;
            }

            $boxesDir[$moduleKey][] = str_replace('.php', '', strtolower(basename($box)));
        }
    }
}
?>

<script>
function resetBox() {
    $(':input','.changeBox')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');

    $('#type').change();
}

$('.deleteMenu').on('click', function(event) {
    $('#modalButton').data('clickurl', $(this).data('clickurl'));
    $('#modalText').html($(this).data('modaltext'));
});

$('#modalButton').on('click', function(event) {
    window.location = $(this).data('clickurl');
});

$(document).ready
(
    function () {
        var itemId = 999;
        $('.sortable').nestedSortable ({
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
            stop: function(event, ui){
                val = ui.item.find('input.hidden_type').val();

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

        $('#menuForm').submit (
            function () {
                $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
            }
        );

        $('#menuForm').on('click', '#menuItemAdd', function () {
            if ($('#title').val() == '') {
                alert('Es muss ein Titel angegeben werden');
                return;
            }

            append = '#sortable';

            if ($('#type').val() != 0 && $('#type').val() != 4 && $('#menukey').val() != 0) {
                id = $('#menukey').val();

                if ($('#sortable #'+id+' ol').length > 0) {

                } else {
                    $('<ol></ol>').appendTo('#sortable #'+id);
                }

                if (!isNaN(id)) {
                    append = '#sortable #list_'+id+' ol';

                    if($(append).length == 0) {
                        $('<ol></ol>').appendTo('#sortable #list_'+id);
                    }
                } else {
                    if($(append).length == 0) {
                        $('<ol></ol>').appendTo('#sortable #'+id);
                    }
                    append = '#sortable #'+id+' ol';
                }

            }

            var modulKey = $('#modulekey').val();
            var boxkey = $('#boxkey').val();

            if (typeof modulKey == "undefined" && typeof boxkey != "undefined")
            {
                boxkeyParts = boxkey.split('_');
                modulKey = boxkeyParts[0];
            }

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][id]" class="hidden_id" value="tmp_'+itemId+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][title]" class="hidden_title" value="'+$('#title').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][href]" class="hidden_href" value="'+$('#href').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][type]" class="hidden_type" value="'+$('#type').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][siteid]" class="hidden_siteid" value="'+$('#siteid').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][boxkey]" class="hidden_boxkey" value="'+$('#boxkey').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][modulekey]" class="hidden_modulekey" value="'+modulKey+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][menukey]" class="hidden_menukey" value="'+$('#menukey').val()+'" />'
                    +'</span></span><span class="title">'+$('#title').val()+'</span><span class="item_delete"><i class="fa fa-times-circle"></i></span><span class="item_edit"><i class="fa fa-edit"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
            }
        );

        $('#menuForm').on('click', '#menuItemEdit', function () {
                if ($('#title').val() == '') {
                    alert('Es muss ein Titel angegeben werden');
                    return;
                }

                var modulKey = $('#modulekey').val();
                var boxkey = $('#boxkey').val();

                if (typeof modulKey == "undefined" && typeof boxkey != "undefined")
                {
                    boxkeyParts = boxkey.split('_');
                    modulKey = boxkeyParts[0];
                }

                $('#'+$('#id').val()).find('.title:first').text($('#title').val());
                $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
                $('#'+$('#id').val()).find('.hidden_href:first').val($('#href').val());
                $('#'+$('#id').val()).find('.hidden_type:first').val($('#type').val());
                $('#'+$('#id').val()).find('.hidden_siteid:first').val($('#siteid').val());
                $('#'+$('#id').val()).find('.hidden_modulekey:first').val(modulKey);
                $('#'+$('#id').val()).find('.hidden_boxkey:first').val($('#boxkey').val());
                $('#'+$('#id').val()).find('.hidden_menukey:first').val($('#menukey').val());
                resetBox();
            }
        );

        $('.sortable').on('click', '.item_delete', function() {
            $(this).closest('li').remove();
        });

        $('#menuForm').on('change', '#type', function() {
            var options = '';

            $('#sortable').find('li').each(function(){
                if ($(this).find('input.hidden_type:first').val() == 0) {
                    options += '<option value="'+$(this).find('input.hidden_id:first').val()+'">'+$(this).find('input.hidden_title:first').val()+'</option>';
                }
            });

            if (options == '' && ($(this).val() == '1' || $(this).val() == '2' || $(this).val() == '3')) {
                alert('Es muss zuerst ein Menü hinzugefügt werden');
                $(this).val(0);
                return;
            }

            menuHtml = '<div class="form-group"><label for="href" class="col-lg-2 control-label">Menü</label>\n\
                        <div class="col-lg-4"><select id="menukey" class="form-control">'+options+'</select></div></div>';

            if ($(this).val() == '0') {
                $('.dyn').html('');
            } else if($(this).val() == '1') {
                $('.dyn').html('<div class="form-group"><label for="href" class="col-lg-2 control-label">Adresse</label>\n\
                                <div class="col-lg-4"><input type="text" class="form-control" id="href" value="http://" /></div></div>'+menuHtml);
            } else if ($(this).val() == '2') {
                 $('.dyn').html('<div class="form-group"><label for="href" class="col-lg-2 control-label">Seite</label>\n\
                                <div class="col-lg-4"><?php if(!empty($pages)) { echo '<select id="siteid" class="form-control">'; foreach($pages as $page){ echo '<option value="'.$page->getId().'">'.$page->getTitle().'</option>';} echo '</select>'; }else { echo 'Keine Seite vorhanden'; } ?></div></div>'+menuHtml);
            } else if ($(this).val() == '3') {
                $('.dyn').html('<div class="form-group"><label for="href" class="col-lg-2 control-label">Modul</label>\n\
                                <div class="col-lg-4"><?php if(!empty($modules)) { echo '<select id="modulekey" class="form-control">'; foreach($modules as $module){ $content = $module->getContentForLocale($this->getTranslator()->getLocale()); echo '<option value="'.$module->getKey().'">'.$content['name'].'</option>';} echo '</select>'; }else { echo 'Keine Module vorhanden'; } ?></div></div>'+menuHtml);
            } else if ($(this).val() == '4') {
                $('.dyn').html('<div class="form-group"><label for="href" class="col-lg-2 control-label">Box</label>\n\
                                <div class="col-lg-4"><?='<select id="boxkey" class="form-control">';
                foreach ($boxesDir as $moDir => $modulBoxes) { foreach($modulBoxes as $boDir) { echo '<option value="'.$moDir.'_'.$boDir.'">'.ucfirst($boDir).'</option>'; }} foreach($boxes as $box){ echo '<option value="'.$box->getId().'">self_'.$box->getTitle().'</option>';} echo '</select>'; ?></div></div>');
            }
        });

        $('#menuForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" id="menuItemAdd" value="Menuitem hinzufügen" class="btn">');
            resetBox();
        });

        $('.sortable').on('click', '.item_edit', function() {
           $('.actions').html('<input type="button" id="menuItemEdit" value="Editieren" class="btn">\n\
                               <input type="button" id="menuItemEditCancel" value="Abbrechen" class="btn">');
           $('#title').val($(this).parent().find('.hidden_title').val());
           $('#type').val($(this).parent().find('.hidden_type').val());
           $('#id').val($(this).closest('li').attr('id'));
           $('#type').change();
           $('#href').val($(this).parent().find('.hidden_href').val());
           $('#siteid').val($(this).parent().find('.hidden_siteid').val());
           $('#boxkey').val($(this).parent().find('.hidden_boxkey').val());
           $('#modulekey').val($(this).parent().find('.hidden_modulekey').val());
           $('#menukey').val($(this).parent().find('.hidden_menukey').val());
        });
    }
);
</script>
