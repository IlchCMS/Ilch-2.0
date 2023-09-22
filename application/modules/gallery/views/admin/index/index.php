<?php

/** @var \Ilch\View $this */

/** @var \Modules\Gallery\Mappers\Gallery $galleryMapper */
$galleryMapper = $this->get('galleryMapper');
/** @var \Modules\Gallery\Models\GalleryItem[]|null $galleryItems */
$galleryItems = $this->get('galleryItems');
/** @var \Modules\Gallery\Mappers\Image $imageMapper */
$imageMapper = $this->get('imageMapper');

/**
 * @param \Modules\Gallery\Models\GalleryItem $item
 * @param \Modules\Gallery\Mappers\Gallery $galleryMapper
 * @param \Ilch\View $obj
 * @param \Modules\Gallery\Mappers\Image $imageMapper
 * @return void
 */
function rec(\Modules\Gallery\Models\GalleryItem $item, \Modules\Gallery\Mappers\Gallery $galleryMapper, \Ilch\View $obj, \Modules\Gallery\Mappers\Image $imageMapper)
{
    $subItems = $galleryMapper->getGalleryItemsByParent($item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    echo '<li id="list_' . $item->getId() . '" class="' . $class . '">';
    echo '<div><span class="disclose"><i class="fa-solid fa-circle-minus"></i>
                    <input type="hidden" class="hidden_id" name="items[' . $item->getId() . '][id]" value="' . $item->getId() . '" />
                    <input type="hidden" class="hidden_title" name="items[' . $item->getId() . '][title]" value="' . $obj->escape($item->getTitle()) . '" />
                    <input type="hidden" class="hidden_desc" name="items[' . $item->getId() . '][desc]" value="' . $obj->escape($item->getDesc()) . '" />
                    <input type="hidden" class="hidden_type" name="items[' . $item->getId() . '][type]" value="' . $item->getType() . '" />
                    <input type="hidden" class="hidden_parentid" name="items[' . $item->getId() . '][parentid]" value="' . $item->getParentId() . '" />
                    <span></span>
                </span>
                <span class="title">' . $obj->escape($item->getTitle()) . '</span>
                <span class="item_delete">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span><span class="item_edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </span>
                <span class="upload" style="float:right; margin-right: 6px;">
                    <a href="javascript:media(' . $item->getId() . ')">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </a>
                </span>
                <span class="view" style="float:right; margin-right: 6px;">
                    <a href="' . $obj->getUrl(['controller' => 'gallery', 'action' => 'treatgallery','id' => $item->getId()]) . '">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </span>
                <span class="count" style="float:right; margin-right: 6px;">' . $imageMapper->getCountImageById($item->getId()) . '</span>
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

<form class="form-horizontal row" id="galleryForm" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <h1><?=$this->getTrans('gallery') ?></h1>
    <div class="col-lg-6">
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
    <div class="col-lg-6 changeBox">
        <input type="hidden" id="id" value="" />
        <div class="row form-group ilch-margin-b">
            <label for="title" class="col-lg-3 control-label">
                <?=$this->getTrans('title') ?>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="title" />
            </div>
        </div>
        <div class="row form-group ilch-margin-b">
            <label for="desc" class="col-lg-3 control-label">
                <?=$this->getTrans('description') ?>
            </label>
            <div class="col-lg-6">
                <textarea class="form-control"
                          id="desc"
                          name="desc"
                          rows="3"
                          cols="45"></textarea>
            </div>
        </div>
        <div class="row form-group ilch-margin-b">
            <label for="type" class="col-lg-3 control-label">
                <?=$this->getTrans('type') ?>
            </label>
            <div class="col-lg-6">
                <select class="form-control" id="type">
                    <option value="0"><?=$this->getTrans('cat') ?></option>
                    <option value="1"><?=$this->getTrans('gallery') ?></option>
                </select>
            </div>
        </div>
        <div class="dyn"></div>
        <div class="col-lg-offset-3 actions">
            <input type="button" class="btn" id="menuItemAdd" value="<?=$this->getTrans('galleryItemAdd') ?>">
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar('saveButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe style="border:0;"></iframe>') ?>
<script>
function resetBox() {
    $(':input', '.changeBox')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected')
        .removeAttr('disabled')
        .removeAttr('title');
    $('#type').change();
}

$(document).ready (
    function () {
        let itemId = 999;

        let entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };

        function escapeHtml (string) {
            return String(string).replace(/[&<>"'`=\/]/g, function (s) {
                return entityMap[s];
            });
        }

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
            let options = '';

            $('#sortable').find('li').each(function() {
                if ($(this).find('input.hidden_type:first').val() === "0") {
                    options += '<option value="'+$(this).find('input.hidden_id:first').val()+'">'+escapeHtml($(this).find('input.hidden_title:first').val())+'</option>';
                }
            });

            if (options === '' && ($(this).val() === '1')) {
                alert(<?=json_encode($this->getTrans('missingCat')) ?>);
                $(this).val(0);
                return;
            }

            let menuHtml = '<div class="form-group"><label for="href" class="col-lg-3 control-label"><?=$this->getTrans('cat') ?></label>\n\
                        <div class="col-lg-6"><select class="form-control" id="menukey">'+options+'</select></div></div>';

            if ($(this).val() === '0') {
                $('.dyn').html('');
            } else if ($(this).val() === '1') {
                $('.dyn').html(menuHtml);
            }
        });

        $('#galleryForm').on('click', '#menuItemAdd', function () {
            if ($('#title').val() === '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                return;
            }

            let append = '#sortable';

            if ($('#type').val() !== "0" && $('#menukey').val() > 0 ) {
                let id = $('#menukey').val();

                if ($('#sortable #'+id+' ol').length > 0) {

                } else {
                    $('<ol></ol>').appendTo('#sortable #'+id);
                }

                if (!isNaN(id)) {
                    append = '#sortable #list_'+id+' ol';

                    if ($(append).length === 0) {
                        $('<ol></ol>').appendTo('#sortable #list_'+id);
                    }
                } else {
                    if ($(append).length === 0) {
                        $('<ol></ol>').appendTo('#sortable #'+id);
                    }
                    append = '#sortable #'+id+' ol';
                }

            }

            let escapedTitle = escapeHtml($('#title').val());

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                    +'<input type="hidden" class="hidden_id" name="items[tmp_'+itemId+'][id]" value="tmp_'+itemId+'" />'
                    +'<input type="hidden" class="hidden_title" name="items[tmp_'+itemId+'][title]" value="'+escapedTitle+'" />'
                    +'<input type="hidden" class="hidden_desc" name="items[tmp_'+itemId+'][desc]" value="'+escapeHtml($('#desc').val())+'" />'
                    +'<input type="hidden" class="hidden_type" name="items[tmp_'+itemId+'][type]" value="'+$('#type').val()+'" />'
                    +'</span></span><span class="title">'+escapedTitle+'</span><span class="item_delete"><i class="fa-solid fa-circle-xmark"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
        });

        $('.sortable').on('click', '.item_edit', function () {
            let parentId = $(this).parent().find('.hidden_parentid').val();
            $('.actions').html('<input type="button" class="btn" id="menuItemEdit" value="<?=$this->getTrans('edit') ?>">\n\
                               <input type="button" class="btn" id="menuItemEditCancel" value="<?=$this->getTrans('cancel') ?>">');
            $('#title').val($(this).parent().find('.hidden_title').val());
            $('#desc').val($(this).parent().find('.hidden_desc').val());
            $('#type').val($(this).parent().find('.hidden_type').val()).change();
            $('#type').attr('disabled', 'disabled');
            $('#type').attr('title', <?=json_encode($this->getTrans('titleCannotChangeTypeOnEdit')) ?>);
            $('#menukey option[value='+parentId+']').attr('selected', 'selected');
            $('#menukey').attr('disabled', 'disabled');
            $('#menukey').attr('title', <?=json_encode($this->getTrans('titleCannotChangeCategoryOnEdit')) ?>);
            $('#id').val($(this).closest('li').attr('id'));
        });

        $('#galleryForm').on('click', '#menuItemEdit', function () {
                if ($('#title').val() === '') {
                    alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                    return;
                }

                $('#'+$('#id').val()).find('.title:first').text($('#title').val());
                $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
                $('#'+$('#id').val()).find('.hidden_desc:first').val($('#desc').val());
                $('#'+$('#id').val()).find('.hidden_type:first').val($('#type').val());
                resetBox();
            }
        );

        $('#galleryForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemAdd" value="<?=$this->getTrans('galleryItemAdd') ?>">');
            resetBox();
        });
    }
);
</script>
<script>
<?=$this->getMedia()
        ->addActionButton($this->getUrl('admin/gallery/gallery/treatgallery/id/' . $this->getRequest()->getParam('id')))
        ->addMediaButton($this->getUrl('admin/media/iframe/multi/type/multi/id/'))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

function reload() {
    setTimeout(function(){window.location=window.location;}, 1000);
}
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
}
</style>
