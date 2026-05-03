<?php

/** @var \Ilch\View $this */

/** @var \Modules\Downloads\Models\DownloadsItem[]|null $downloadsItems */
$downloadsItems = $this->get('downloadsItems');

/**
 * @param \Modules\Downloads\Models\DownloadsItem $item
 * @param \Ilch\View $obj
 */
function rec(\Modules\Downloads\Models\DownloadsItem $item, \Ilch\View $obj)
{
    /** @var \Modules\Downloads\Mappers\Downloads $downloadsMapper */
    $downloadsMapper = $obj->get('downloadsMapper');
    /** @var \Modules\Downloads\Mappers\File $fileMapper */
    $fileMapper = $obj->get('fileMapper');

    $subItems = $downloadsMapper->getDownloadsItemsByParent($item->getId());
    $fileCount = $fileMapper->getCountOfFilesByItemId($item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    echo '<li id="list_' . $item->getId() . '" class="' . $class . '">';
    echo '<div><span class="disclose"><i class="fa-solid fa-circle-minus"></i>
                    <input type="hidden" class="hidden_id" name="items[' . $item->getId() . '][id]" value="' . $item->getId() . '" />
                    <input type="hidden" class="hidden_title" name="items[' . $item->getId() . '][title]" value="' . $item->getTitle() . '" />
                    <input type="hidden" class="hidden_desc" name="items[' . $item->getId() . '][desc]" value="' . $item->getDesc() . '" />
                    <input type="hidden" class="hidden_type" name="items[' . $item->getId() . '][type]" value="' . $item->getType() . '" />
                    <input type="hidden" class="hidden_access" name="items[' . $item->getId() . '][access]" value="' . $item->getAccess() . '" />
                    <span></span>
                </span>
                <span class="title">' . $obj->escape($item->getTitle()) . '</span>
                <span class="item_delete">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span><span class="item_edit">
                    <i class="fa-solid fa-edit"></i>
                </span>
                <span class="upload" style="float:right; margin-right: 6px;">
                    <a href="javascript:media(' . $item->getId() . ')">
                        <i class="fa-solid fa-cloud-upload"></i>
                    </a>
                </span>
                <span class="view" style="float:right; margin-right: 6px;">
                    <a href="' . $obj->getUrl(['controller' => 'downloads', 'action' => 'treatdownloads','id' => $item->getId()]) . '">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </span>
                <span class="count" style="float:right; margin-right: 6px;">' . $fileCount . '</span>
            </div>';

    if (!empty($subItems)) {
        echo '<ol>';

        foreach ($subItems as $subItem) {
            rec($subItem, $obj);
        }

        echo '</ol>';
    }

    echo '</li>';
}
?>

<link href="<?=$this->getModuleUrl('../downloads/static/css/admincenter.css') ?>" rel="stylesheet">
<form id="downloadsForm" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row">
        <h1><?=$this->getTrans('downloads') ?></h1>
        <div class="col-xl-6">
            <ol id="sortable" class="sortable">
                <?php
                if (!empty($downloadsItems)) {
                    foreach ($downloadsItems as $item) {
                        rec($item, $this);
                    }
                }
                ?>
            </ol>
        </div>
        <div class="col-xl-6 changeBox">
            <input type="hidden" id="id" value="" />
            <div class="row mb-3">
                <label for="title" class="col-xl-3 col-form-label">
                    <?=$this->getTrans('title') ?>
                </label>
                <div class="col-xl-6">
                    <input type="text" class="form-control" id="title" />
                </div>
            </div>
            <div class="row mb-3">
                <label for="desc" class="col-xl-3 col-form-label">
                    <?=$this->getTrans('description') ?>
                </label>
                <div class="col-xl-6">
                    <textarea class="form-control"
                        id="desc"
                        name="desc"
                        rows="3"
                        cols="45"></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label for="type" class="col-xl-3 col-form-label">
                    <?=$this->getTrans('type') ?>
                </label>
                <div class="col-xl-6">
                    <select class="form-select" id="type">
                        <option value="0"><?=$this->getTrans('cat') ?></option>
                        <option value="1"><?=$this->getTrans('downloads') ?></option>
                    </select>
                </div>
            </div>
            <div class="dyn"></div>
            <div class="row mb-3">
                <label for="access" class="col-xl-3 col-form-label"><?=$this->getTrans('access') ?></label>
                <div class="col-xl-6">
                    <select class="choices-select form-control"
                            id="access"
                            name="user[groups][]"
                            data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>"
                            multiple>
                        <?php
                        /** @var \Modules\User\Models\Group $group */
                        foreach ($this->get('userGroupList') as $group) : ?>\n\
                            <option value="<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-xl-3 col-form-label"></label>
                <div class="col-xl-6 actions">
                    <input type="button" class="btn btn-outline-secondary" id="menuItemAdd" value="<?=$this->getTrans('downloadsItemAdd') ?>">
                </div>
            </div>
        </div>
        <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
        <?=$this->getSaveBar() ?>
    </div>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
function resetBox() {
    $(':input','.changeBox')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
    choicesAccess.removeActiveItems();

    $('#type').change();
}

$(document).ready (
    function () {
        let itemId = 999;
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

        let choicesAccess;
        choicesAccess = new Choices('#access', {
            ...choicesOptions,
            searchEnabled: true
        });

        function escapeHtml(unsafe) {
            if (!unsafe) return ""; // Handle null/undefined
            return unsafe
                .replace(/&/g, "&amp;")   // First: escape & to avoid double-escaping entities
                .replace(/</g, "&lt;")    // Escape <
                .replace(/>/g, "&gt;")    // Escape >
                .replace(/"/g, "&quot;")  // Escape " (for attributes)
                .replace(/'/g, "&#39;");  // Escape ' (for attributes)
        }

        $('.disclose').on('click', function () {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
            $(this).find('i').toggleClass('fa-circle-minus').toggleClass('fa-circle-plus');
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
            let options = '';

            $('#sortable').find('li').each(function() {
                if ($(this).find('input.hidden_type:first').val() == 0) {
                    options += '<option value="'+$(this).find('input.hidden_id:first').val()+'">'+escapeHtml($(this).find('input.hidden_title:first').val())+'</option>';
                }
            });

            if (options == '' && ($(this).val() == '1')) {
                alert(<?=json_encode($this->getTrans('missingCat')) ?>);
                $(this).val(0);
                return;
            }

            const menuHtml = '<div class="row mb-3"><label for="href" class="col-xl-3 col-form-label"><?=$this->getTrans('cat') ?></label>\n\
                              <div class="col-xl-6"><select class="form-select" id="menukey">'+options+'</select></div></div>';

            if ($(this).val() == '0') {
                $('.dyn').html('');
            } else if ($(this).val() == '1') {
                $('.dyn').html(menuHtml);
            }
        });

        $('#downloadsForm').on('click', '#menuItemAdd', function () {
                    if ($('#title').val() == '') {
                        alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                        return;
                    }

            let append = '#sortable';
            let id;

            if ($('#type').val() != 0 && $('#menukey').val() != 0) {
                id = $('#menukey').val();

                if ($('#sortable #' + id + ' ol').length > 0) {

                } else {
                    $('<ol></ol>').appendTo('#sortable #' + id);
                }

                if (!isNaN(id)) {
                    append = '#sortable #list_' + id + ' ol';

                    if ($(append).length == 0) {
                        $('<ol></ol>').appendTo('#sortable #list_' + id);
                    }
                } else {
                    if ($(append).length == 0) {
                        $('<ol></ol>').appendTo('#sortable #' + id);
                    }
                    append = '#sortable #' + id + ' ol';
                }

            }

            const accessVal = choicesAccess.getValue(true).join(',');

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                    +'<input type="hidden" class="hidden_id" name="items[tmp_'+itemId+'][id]" value="tmp_'+itemId+'" />'
                    +'<input type="hidden" class="hidden_title" name="items[tmp_'+itemId+'][title]" value="'+$('#title').val()+'" />'
                    +'<input type="hidden" class="hidden_desc" name="items[tmp_'+itemId+'][desc]" value="'+$('#desc').val()+'" />'
                    +'<input type="hidden" class="hidden_type" name="items[tmp_'+itemId+'][type]" value="'+$('#type').val()+'" />'
                    +'<input type="hidden" class="hidden_access" name="items[tmp_'+itemId+'][access]" value="'+ accessVal +'" />'
                    +'</span></span><span class="title">'+$('#title').val()+'</span><span class="item_delete"><i class="fa-solid fa-circle-xmark"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
            }
        );

        $('.sortable').on('click', '.item_edit', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemEdit" value="<?=$this->getTrans('edit') ?>">\n\
                               <input type="button" class="btn" id="menuItemEditCancel" value="<?=$this->getTrans('cancel') ?>">');
            $('#title').val($(this).parent().find('.hidden_title').val());
            $('#desc').val($(this).parent().find('.hidden_desc').val());
            $('#type').val($(this).parent().find('.hidden_type').val());
            $('#id').val($(this).closest('li').attr('id'));
            $('#type').change();

            let hidden_access_values = $(this).parent().find('.hidden_access').val().split(',').map(value => value.trim());
            choicesAccess.removeActiveItems();
            choicesAccess.setChoiceByValue(hidden_access_values);
        });

        $('#downloadsForm').on('click', '#menuItemEdit', function () {
            if ($('#title').val() == '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                return;
            }

            const accessVal = choicesAccess.getValue(true).join(',');
            $('#'+$('#id').val()).find('.title:first').text($('#title').val());
            $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
            $('#'+$('#id').val()).find('.hidden_desc:first').val($('#desc').val());
            $('#'+$('#id').val()).find('.hidden_type:first').val($('#type').val());
            $('#'+$('#id').val()).find('.hidden_access:first').val(accessVal);
            resetBox();
        });

        $('#downloadsForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemAdd" value="Menuitem hinzufügen">');
            resetBox();
        });
    }
);
</script>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/multi/type/file/id/' . $this->getRequest()->getParam('id')))
        ->addActionButton($this->getUrl('admin/downloads/downloads/treatdownloads/id/' . $this->getRequest()->getParam('id')))
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>

function reload() {
    setTimeout(function(){window.location.reload(1);}, 1000);
}
</script>
