<?php
$menuItems = $this->get('menuItems');
$menuMapper = $this->get('menuMapper');

function rec($item, $menuMapper)
{
    $subItems = $menuMapper->getMenuItemsByParent(1, $item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    echo '<li id="list_'.$item->getId().'" class="'.$class.'">';
    echo '<div><span class="disclose"><i class="fa fa-minus-circle"></i>
                    <input type="hidden" name="items['.$item->getId().'][id]" class="hidden_id" value="'.$item->getId().'" />
                    <input type="hidden" name="items['.$item->getId().'][title]" class="hidden_title" value="'.$item->getTitle().'" />
                    <input type="hidden" name="items['.$item->getId().'][href]" class="hidden_href" value="'.$item->getHref().'" />
                    <span></span>
                </span><span class="title">'.$item->getTitle().'</span><span class="item_delete"><i class="fa fa-times-circle"></i></span><span class="item_edit"><i class="fa fa-edit"></i></span></div>';

    if (!empty($subItems)) {
        echo '<ol>';

        foreach ($subItems as $subItem) {
            rec($subItem, $menuMapper);
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
<form class="form-horizontal" id="menuForm" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <legend><?php echo $this->trans('menuChange'); ?></legend>
    <div class="row">
        <div class="col-md-6">
          <ol id="sortable" class="sortable">
                <?php
                    if (!empty($menuItems)) {
                        foreach ($menuItems as $item) {
                            rec($item, $menuMapper);
                        }
                    }
                ?>
            </ol>
        </div>
        <div class="col-md-6 changeBox">
            <input type="hidden" id="id" value="" />
            <div class="form-group">
                <label for="maintenanceMode" class="col-lg-2 control-label">
                    Menütitel
                </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="title" />
                </div>
            </div>
            <div class="form-group">
                <label for="maintenanceMode" class="col-lg-2 control-label">
                    Adresse
                </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="href" value="http://" />
                </div>
            </div>
            <div class="actions">
                <input type="button" id="menuItemAdd" value="<?php echo $this->trans('menuItemAdd'); ?>" class="btn">
            </div>
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php echo $this->trans('saveButton'); ?>
        </button>
    </div>
</form>
<script>
    $(document).ready
    (
        function () {
            var itemId = 999;
            $('.sortable').nestedSortable
            ({
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
                startCollapsed: false
            });
            


            $('.disclose').on('click', function () {
                $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
                $(this).find('i').toggleClass('fa-minus-circle').toggleClass('fa-plus-circle');
            });

            $('#menuForm').submit
            (
                function () {
                    $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
                }
            );

            $('#menuForm').on('click', '#menuItemAdd', function () {
                        $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                                +'<input type="hidden" name="items[tmp_'+itemId+'][id]" class="hidden_id" value="tmp_'+itemId+'" />'
                                +'<input type="hidden" name="items[tmp_'+itemId+'][title]" class="hidden_title" value="'+$('#title').val()+'" />'
                                +'<input type="hidden" name="items[tmp_'+itemId+'][href]" class="hidden_href" value="'+$('#href').val()+'" />'
                                +'</span></span>'+$('#title').val()+'<span class="item_delete"><i class="fa fa-times-circle"></i></span><span class="item_edit"><i class="fa fa-edit"></i></span></div></li>').appendTo('#sortable');
                        itemId++;

                        $(':input','.changeBox')
                          .not(':button, :submit, :reset, :hidden')
                          .val('')
                          .removeAttr('checked')
                          .removeAttr('selected');
                    }
            );
            
            $('#menuForm').on('click', '#menuItemEdit', function () {
                        $('#'+$('#id').val()).find('.title:first').text($('#title').val());
                        $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
                        $('#'+$('#id').val()).find('.hidden_href:first').val($('#href').val());
                        $(':input','.changeBox')
                          .not(':button, :submit, :reset, :hidden')
                          .val('')
                          .removeAttr('checked')
                          .removeAttr('selected');
                    }
            );
            
            $('.sortable').on('click', '.item_delete', function() {
                $(this).closest('li').remove();
            });
            
            $('#menuForm').on('click', '#menuItemEditCancel', function() {
                $('.actions').html('<input type="button" id="menuItemAdd" value="Menuitem hinzufügen" class="btn">');
                   $(':input','.changeBox')
                          .not(':button, :submit, :reset, :hidden')
                          .val('')
                          .removeAttr('checked')
                          .removeAttr('selected');
            });
            
            $('.sortable').on('click', '.item_edit', function() {
               $('.actions').html('<input type="button" id="menuItemEdit" value="Editieren" class="btn">\n\
                                   <input type="button" id="menuItemEditCancel" value="Abbrechen" class="btn">'); 
               $('#title').val($(this).parent().find('.hidden_title').val());
               $('#href').val($(this).parent().find('.hidden_href').val());
               $('#id').val($(this).closest('li').attr('id'));
               
            });
        }
    );
</script>
