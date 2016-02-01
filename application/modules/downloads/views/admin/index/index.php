<?php
$downloadsMapper = $this->get('downloadsMapper');
$downloadsItems = $this->get('downloadsItems');
$fileMapper = $this->get('fileMapper');

function rec($item, $downloadsMapper, $obj, $fileMapper)
{
    $subItems = $downloadsMapper->getDownloadsItemsByParent('1', $item->getId());
    $file = $fileMapper->getCountFileById($item->getId());
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
                    <a href="'.$obj->getUrl(array('controller' => 'downloads', 'action' => 'treatdownloads','id' => $item->getId())).'">
                        <i class="fa fa-eye"></i>
                    </a>
                </span>
                <span class="count" style="float:right; margin-right: 6px;">'. count($file).'</span>
            </div>';

    if (!empty($subItems)) {
        echo '<ol>';

        foreach ($subItems as $subItem) {
            rec($subItem, $downloadsMapper, $obj, $fileMapper);
        }

        echo '</ol>';
    }

    echo '</li>';
}
?>

<form class="form-horizontal" id="downloadsForm" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('downloads') ?></legend>
    <div class="col-lg-6">
        <ol id="sortable" class="sortable">
            <?php
                if (!empty($downloadsItems)) {
                    foreach ($downloadsItems as $item) {
                        rec($item, $downloadsMapper, $this, $fileMapper);
                    }
                }
            ?>
        </ol>
    </div>
    <div class="col-lg-6 changeBox">
        <input type="hidden" id="id" value="" />
        <div class="form-group">
            <label for="title" class="col-lg-3 control-label">
                <?=$this->getTrans('title') ?>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="title" />
            </div>
        </div>
        <div class="form-group">
            <label for="desc" class="col-lg-3 control-label">
                <?=$this->getTrans('description') ?>
            </label>
            <div class="col-lg-6">
                <textarea class="form-control" rows="3" cols="45" type="text" id="desc" name="desc"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="col-lg-3 control-label">
                <?=$this->getTrans('type') ?>
            </label>
            <div class="col-lg-6">
                <select id="type" class="form-control">
                    <option value="0"><?=$this->getTrans('cat') ?></option>
                    <option value="1"><?=$this->getTrans('downloads') ?></option>
                </select>
            </div>
        </div>
        <div class="dyn"></div>
        <div class="col-lg-offset-3 actions">
            <input type="button" id="menuItemAdd" value="<?=$this->getTrans('downloadsItemAdd') ?>" class="btn">
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar('saveButton') ?>
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
        $('#downloadsForm').submit (
            function () {
                $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
            }
        );
        $('.sortable').on('click', '.item_delete', function() {
            $(this).closest('li').remove();
        });

        $('#downloadsForm').on('change', '#type', function() {
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

            menuHtml = '<div class="form-group"><label for="href" class="col-lg-3 control-label">Menü</label>\n\
                        <div class="col-lg-6"><select id="menukey" class="form-control">'+options+'</select></div></div>';

            if ($(this).val() == '0') {
                $('.dyn').html('');
            } else if($(this).val() == '1') {
                $('.dyn').html(menuHtml);
            }
        });

        $('#downloadsForm').on('click', '#menuItemAdd', function () {
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
            }
        );

        $('.sortable').on('click', '.item_edit', function() {
           $('.actions').html('<input type="button" id="menuItemEdit" value="Editieren" class="btn">\n\
                               <input type="button" id="menuItemEditCancel" value="Abbrechen" class="btn">');
           $('#title').val($(this).parent().find('.hidden_title').val());
           $('#desc').val($(this).parent().find('.hidden_desc').val());
           $('#type').val($(this).parent().find('.hidden_type').val());
           $('#id').val($(this).closest('li').attr('id'));
           $('#type').change();
        });

        $('#downloadsForm').on('click', '#menuItemEdit', function () {
                if ($('#title').val() == '') {
                    alert('Es muss ein Titel angegeben werden');
                    return;
                }

                $('#'+$('#id').val()).find('.title:first').text($('#title').val());
                $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
                $('#'+$('#id').val()).find('.hidden_desc:first').val($('#desc').val());
                $('#'+$('#id').val()).find('.hidden_type:first').val($('#type').val());
                resetBox();
            }
        );

        $('#downloadsForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" id="menuItemAdd" value="Menuitem hinzufügen" class="btn">');
            resetBox();
        });
    }
);
</script>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/multi/type/file/id/'.$this->getRequest()->getParam('id')))
        ->addActionButton($this->getUrl('admin/downloads/downloads/treatdownloads/id/'.$this->getRequest()->getParam('id')))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

function reload(){
    setTimeout(function(){window.location.reload(1);}, 1000);
};
</script>

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

.sortable > li > div > .upload {
    display: none;
}

.sortable > li > div > .view {
    display: none;
}

.sortable > li > div > .count {
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
