<?php
$menuItems = $this->get('menuItems');
$menuMapper = $this->get('menuMapper');
$pages = $this->get('pages');
$modules = $this->get('modules');
$boxes = $this->get('boxes');

function rec($item, $menuMapper, $obj)
{
    $subItems = $menuMapper->getMenuItemsByParent($obj->get('menu')->getId(), $item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    if ($item->getType() == 4) {
        $class .= ' mjs-nestedSortable-no-nesting';
    }

    echo '<li id="list_'.$item->getId().'" class="'.$class.'">';
    echo '<div><span class="disclose"><i class="fa fa-minus-circle"></i>
                    <input type="hidden" name="items['.$item->getId().'][id]" class="hidden_id" value="'.$item->getId().'" />
                    <input type="hidden" name="items['.$item->getId().'][title]" class="hidden_title" value="'.$item->getTitle().'" />
                    <input type="hidden" name="items['.$item->getId().'][href]" class="hidden_href" value="'.$item->getHref().'" />
                    <input type="hidden" name="items['.$item->getId().'][type]" class="hidden_type" value="'.$item->getType().'" />
                    <input type="hidden" name="items['.$item->getId().'][siteid]" class="hidden_siteid" value="'.$item->getSiteId().'" />
                    <input type="hidden" name="items['.$item->getId().'][boxkey]" class="hidden_boxkey" value="'.$item->getBoxKey().'" />
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
<style>
    .item_delete {
        float: right;
        cursor: pointer;
    }

    .item_edit {
        margin-right: 6px;
        float: right;
        cursor: pointer;
    }

    ol.sortable, ol.sortable ol {
        margin: 0 0 0 25px;
        padding: 0;
        list-style-type: none;
    }

    ol.sortable {
        margin: 0;
    }

    .sortable li {
        margin: 5px 0 0 0;
        padding: 0;
    }

    .sortable li div  {
        border: 1px solid #d4d4d4;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border-color: #D4D4D4 #D4D4D4 #BCBCBC;
        padding: 6px;
        margin: 0;
        cursor: move;
        background: #f6f6f6;
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
    }

    .sortable li.mjs-nestedSortable-branch div {
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#f0ece9 100%);

    }

    .sortable li.mjs-nestedSortable-leaf div {
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#bcccbc 100%);

    }

    li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
        border-color: #999;
        background: #fafafa;
    }

    .disclose {
        cursor: pointer;
        width: 18px;
        display: none;
    }

    .sortable li.mjs-nestedSortable-collapsed > ol {
        display: none;
    }

    .sortable li.mjs-nestedSortable-branch > div > .disclose {
        display: inline-block;
    }

    .placeholder {
        outline: 1px dashed #4183C4;
        /*-webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: -1px;*/
    }
</style>
<form class="form-horizontal" id="menuForm" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName(), 'menu' => $this->get('menu')->getId())); ?>">
    <?php echo $this->getTokenField(); ?>
    <ul class="nav nav-tabs">
        <?php
        $iMenu = 1;

        foreach ($this->get('menus') as $menu) {
            $active = '';

            if($menu->getId() == $this->get('menu')->getId()) {
                $active = 'active';
            }
            echo '<li class="'.$active.'">'
                    . '<a href="'.$this->url(array('menu' => $menu->getId())).'">'.$this->trans('menu').' '.$iMenu.'</a>'
                    . '</li>';
            $iMenu++;
        }
        ?>
      <li><a href="<?php echo $this->url(array('action' => 'add')); ?>">+</a></li>
    </ul>
    <br />
    <legend><?php echo $this->trans('menuChange'); ?></legend>
        <div class="col-xs-6">
            <ol id="sortable" class="sortable">
                <?php
                    if (!empty($menuItems)) {
                        foreach ($menuItems as $item) {
                            rec($item, $menuMapper, $this);
                        }
                    }
                ?>
            </ol>
        </div>
        <div class="col-xs-1"></div>
        <div class="col-xs-5 changeBox">
            <input type="hidden" id="id" value="" />
            <div class="form-group">
                <label for="title" class="col-xs-2 control-label">
                    Itemtitel
                </label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="title" />
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-xs-2 control-label">
                    Itemtyp
                </label>
                <div class="col-xs-4">
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
                <input type="button" id="menuItemAdd" value="<?php echo $this->trans('menuItemAdd'); ?>" class="btn">
            </div>
        </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php echo $this->trans('saveButton'); ?>
        </button>
        <span class="deleteMenu clickable btn pull-right"
              data-clickurl="<?php echo $this->url(array('action' => 'delete', 'id' => $this->get('menu')->getId())); ?>"
              data-toggle="modal"
              data-target="#deleteModal"
              data-modaltext="<?php echo $this->escape($this->trans('askIfDeleteMenu', $this->get('menu')->getTitle())); ?>">
                  <?php echo $this->trans('deleteMenu'); ?>
        </span>

    </div>
</form>
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
                helper:	'clone',
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
                append = '#sortable';

                if ($('#type').val() != 0 && $('#type').val() != 4 && $('#menukey').val() != 0) {
                    id = $('#menukey').val();

                    if ($('#sortable #'+id+' ol').length > 0) {

                    } else {
                        $('<ol></ol>').appendTo('#sortable #'+id);
                    }

                    if (!isNaN(id)) {
                        append = '#sortable #list_'+id+' ol';
                    } else {
                        append = '#sortable #'+id+' ol';
                    }
                }

                $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][id]" class="hidden_id" value="tmp_'+itemId+'" />'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][title]" class="hidden_title" value="'+$('#title').val()+'" />'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][href]" class="hidden_href" value="'+$('#href').val()+'" />'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][type]" class="hidden_type" value="'+$('#type').val()+'" />'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][siteid]" class="hidden_siteid" value="'+$('#siteid').val()+'" />'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][boxkey]" class="hidden_boxkey" value="'+$('#boxkey').val()+'" />'
                        +'<input type="hidden" name="items[tmp_'+itemId+'][modulekey]" class="hidden_modulekey" value="'+$('#modulekey').val()+'" />'
                        +'</span></span>'+$('#title').val()+'<span class="item_delete"><i class="fa fa-times-circle"></i></span><span class="item_edit"><i class="fa fa-edit"></i></span></div></li>').appendTo(append);
                itemId++;
                resetBox();
                }
            );

            $('#menuForm').on('click', '#menuItemEdit', function () {
                    $('#'+$('#id').val()).find('.title:first').text($('#title').val());
                    $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
                    $('#'+$('#id').val()).find('.hidden_href:first').val($('#href').val());
                    $('#'+$('#id').val()).find('.hidden_type:first').val($('#type').val());
                    $('#'+$('#id').val()).find('.hidden_siteid:first').val($('#siteid').val());
                    $('#'+$('#id').val()).find('.hidden_modulekey:first').val($('#modulekey').val());
                    $('#'+$('#id').val()).find('.hidden_boxkey:first').val($('#boxkey').val());
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

                menuHtml = '<div class="form-group"><label for="href" class="col-xs-2 control-label">Menü</label>\n\
                            <div class="col-xs-4"><select id="menukey" class="form-control">'+options+'</select></div></div>';

                if ($(this).val() == '0') {
                    $('.dyn').html('');
                } else if($(this).val() == '1') {
                    $('.dyn').html('<div class="form-group"><label for="href" class="col-xs-2 control-label">Adresse</label>\n\
                                    <div class="col-xs-4"><input type="text" class="form-control" id="href" value="http://" /></div></div>'+menuHtml);
                } else if ($(this).val() == '2') {
                     $('.dyn').html('<div class="form-group"><label for="href" class="col-xs-2 control-label">Seite</label>\n\
                                    <div class="col-xs-4"><?php if(!empty($pages)) { echo '<select id="siteid" class="form-control">'; foreach($pages as $page){ echo '<option value="'.$page->getId().'">'.$page->getTitle().'</option>';} echo '</select>'; }else { echo 'Keine Seite vorhanden'; } ?></div></div>'+menuHtml);
                } else if ($(this).val() == '3') {
                    $('.dyn').html('<div class="form-group"><label for="href" class="col-xs-2 control-label">Modul</label>\n\
                                    <div class="col-xs-4"><?php if(!empty($modules)) { echo '<select id="modulekey" class="form-control">'; foreach($modules as $module){ echo '<option value="'.$module->getKey().'">'.$module->getName($this->getTranslator()->getLocale()).'</option>';} echo '</select>'; }else { echo 'Keine Seite vorhanden'; } ?></div></div>'+menuHtml);
                } else if ($(this).val() == '4') {
                    $('.dyn').html('<div class="form-group"><label for="href" class="col-xs-2 control-label">Box</label>\n\
                                    <div class="col-xs-4"><?php echo '<select id="boxkey" class="form-control">';
                    foreach (glob(APPLICATION_PATH.'/boxes/*') as $path) { echo '<option value="'.basename($path).'">'.basename($path).'</option>'; } foreach($boxes as $box){ echo '<option value="'.$box->getId().'">self_'.$box->getTitle().'</option>';} echo '</select>'; ?></div></div>');
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
            });
        }
    );
</script>
