<?php
$galleryMapper = $this->get('galleryMapper');
$galleryItems = $this->get('galleryItems');
$imageMapper = $this->get('imageMapper');

function rec($item, $galleryMapper, $obj, $imageMapper)
{
    $subItems = $galleryMapper->getGalleryItemsByParent($item->getUserId(), '1', $item->getId());
    $image = $imageMapper->getCountImageById($item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    echo '<li id="list_'.$item->getId().'" class="'.$class.'">';
    echo '<div><span class="disclose"><i class="fa fa-minus-circle"></i>
                    <input type="hidden" name="items['.$item->getId().'][id]" class="hidden_id" value="'.$item->getId().'" />
                    <input type="hidden" name="items['.$item->getId().'][title]" class="hidden_title" value="'.$item->getTitle().'" />
                    <input type="hidden" name="items['.$item->getId().'][desc]" class="hidden_desc" value="'.$item->getDesc().'" />
                    <input type="hidden" name="items['.$item->getId().'][type]" class="hidden_type" value="'.$item->getType().'" />
                    <span></span>
                </span>
                <span class="title">'.$item->getTitle().'</span>
                <span class="item_delete">
                    <i class="fa fa-times-circle"></i>
                </span><span class="item_edit">
                    <i class="fa fa-edit"></i>
                </span>
                <span class="upload" style="float:right; margin-right: 6px;">
                    <a href="javascript:media('.$item->getId().')">
                        <i class="fa fa-cloud-upload"></i>
                    </a>
                </span>
                <span class="view" style="float:right; margin-right: 6px;">
                    <a href="'.$obj->getUrl(array('action' => 'treatgallery', 'id' => $item->getId())).'">
                        <i class="fa fa-eye"></i>
                    </a>
                </span>
                <span class="count" style="float:right; margin-right: 6px;">'.count($image).'</span>
            </div>';

    if (!empty($subItems)) {
        echo '<ol>';

        foreach ($subItems as $subItem) {
            rec($subItem, $galleryMapper, $obj, $imageMapper);
        }

        echo '</ol>';
    }

    echo '</li>';
}
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div class="modal fade" id="MediaModal" tabindex="-1" role="dialog" aria-labelledby="MediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" 
                        class="close" 
                        data-dismiss="modal" 
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title" id="MediaModalLabel">Media</h4>
            </div>
            <div class="modal-body"><iframe frameborder="0"></iframe></div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-default" 
                        data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('menuGallery') ?></legend>
            <?php if ($profil->getOptGallery() != 0 AND $this->get('galleryAllowed') != 0): ?>
                <form class="form-horizontal" id="galleryForm" method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <div class="col-lg-5">
                        <ol id="sortable" class="sortable">
                            <?php
                                if (!empty($galleryItems)) {
                                    foreach ($galleryItems as $item) {
                                        rec($item, $galleryMapper, $this, $imageMapper);
                                    }
                                }
                            ?>
                        </ol>
                    </div>
                    <div class="col-lg-7 changeBox">
                        <input type="hidden" id="id" value="" />
                        <div class="form-group">
                            <label for="title" class="col-lg-4 control-label">
                                <?=$this->getTrans('title') ?>:
                            </label>
                            <div class="col-lg-8">
                                <input class="form-control"
                                       type="text"
                                       name="title"
                                       id="title" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="desc" class="col-lg-4 control-label">
                                <?=$this->getTrans('description') ?>:
                            </label>
                            <div class="col-lg-8">
                                <textarea class="form-control"
                                          id="desc"
                                          rows="8"
                                          name="desc"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-lg-4 control-label">
                                <?=$this->getTrans('type') ?>:
                            </label>
                            <div class="col-lg-8">
                                <select id="type" class="form-control">
                                    <option value="0"><?=$this->getTrans('cat') ?></option>
                                    <option value="1"><?=$this->getTrans('gallery') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="dyn"></div>
                        <div class="col-lg-offset-4 actions">
                            <input type="button" id="menuItemAdd" value="<?=$this->getTrans('galleryItemAdd') ?>" class="btn">
                        </div>
                    </div>
                    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
                    <div class="col-lg-5">
                        <?=$this->getSaveBar('saveButton', 'Gallery') ?>
                    </div>
                </form>
            <?php else: ?>
                <?=$this->getTrans('usergalleryNotAllowed') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function resetBox() {
    $(':input','.changeBox')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');

    $('#type').change();
}

$(document).ready (
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
            maxLevels: 2,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false,
            protectRoot: true
        });
        $('.disclose').on('click', function () {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
            $(this).find('i').toggleClass('fa-minus-circle').toggleClass('fa-plus-circle');
        });
        $('#galleryForm').submit (
            function () {
                $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
            }
        );
        $('.sortable').on('click', '.item_delete', function() {
            $(this).closest('li').remove();
        });

        $('#galleryForm').on('change', '#type', function() {
            var options = '';

            $('#sortable').find('li').each(function(){
                if ($(this).find('input.hidden_type:first').val() == 0) {
                    options += '<option value="'+$(this).find('input.hidden_id:first').val()+'">'+$(this).find('input.hidden_title:first').val()+'</option>';
                }
            });

            if (options == '' && ($(this).val() == '1')) {
                alert('Es muss zuerst ein Menü hinzugefügt werden');
                $(this).val(0);
                return;
            }

            menuHtml = '<div class="form-group"><label for="href" class="col-lg-4 control-label">Kategorie:</label>\n\
                        <div class="col-lg-8"><select id="menukey" class="form-control">'+options+'</select></div></div>';

            if ($(this).val() == '0') {
                $('.dyn').html('');
            } else if($(this).val() == '1') {
                $('.dyn').html(menuHtml);
            }
        });

        $('#galleryForm').on('click', '#menuItemAdd', function () {
            if ($('#title').val() == '') {
                alert('Es muss ein Titel angegeben werden');
                return;
            }

            append = '#sortable';

            if ($('#type').val() != 0 && $('#menukey').val() != 0 ) {
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

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][id]" class="hidden_id" value="tmp_'+itemId+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][title]" class="hidden_title" value="'+$('#title').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][desc]" class="hidden_desc" value="'+$('#desc').val()+'" />'
                    +'<input type="hidden" name="items[tmp_'+itemId+'][type]" class="hidden_type" value="'+$('#type').val()+'" />'
                    +'</span></span><span class="title">'+$('#title').val()+'</span><span class="item_delete"><i class="fa fa-times-circle"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
        });

        $('.sortable').on('click', '.item_edit', function() {
           $('.actions').html('<input type="button" id="menuItemEdit" value="Editieren" class="btn">\n\
                               <input type="button" id="menuItemEditCancel" value="Abbrechen" class="btn">');
           $('#title').val($(this).parent().find('.hidden_title').val());
           $('#desc').val($(this).parent().find('.hidden_desc').val());
           $('#type').val($(this).parent().find('.hidden_type').val());
           $('#id').val($(this).closest('li').attr('id'));
           $('#type').change();
        });

        $('#galleryForm').on('click', '#menuItemEdit', function () {
            if ($('#title').val() == '') {
                alert('Es muss ein Titel angegeben werden');
                return;
            }

            $('#'+$('#id').val()).find('.title:first').text($('#title').val());
            $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
            $('#'+$('#id').val()).find('.hidden_desc:first').val($('#desc').val());
            $('#'+$('#id').val()).find('.hidden_type:first').val($('#type').val());
            resetBox();
        });

        $('#galleryForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" id="menuItemAdd" value="Menuitem hinzufügen" class="btn">');
            resetBox();
        });
    }
);
</script>
<script>
<?=$this->getMedia()
        ->addActionButton($this->getUrl('user/panel/treatgallery/id/'.$this->getRequest()->getParam('id')))
        ->addMediaButton($this->getUrl('user/iframe/multi/type/multi/id/'))
        ->addUploadController($this->getUrl('user/panel/uploadgallery'))
?>

function reload(){
    setTimeout(function(){window.location.reload(1);}, 1000);
};
</script>
