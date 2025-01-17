<?php

/** @var \Ilch\View $this */

use Modules\Forum\Models\ForumItem;

/** @var ForumItem|null $forumItems */
$forumItems = $this->get('forumItems');

/**
 * @param ForumItem $item
 * @param \Ilch\View $obj
 */
function rec(ForumItem $item, \Ilch\View $obj)
{
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if (empty($item->getSubItems())) {
        $class = 'mjs-nestedSortable-leaf';
    }

    echo '<li id="list_' . $item->getId() . '" class="' . $class . '">
        <div><span class="disclose"><i class="fa-solid fa-circle-minus"></i>
            <input type="hidden" class="hidden_id" name="items[' . $item->getId() . '][id]" value="' . $item->getId() . '" />
            <input type="hidden" class="hidden_title" name="items[' . $item->getId() . '][title]" value="' . $item->getTitle() . '" />
            <input type="hidden" class="hidden_desc" name="items[' . $item->getId() . '][desc]" value="' . $item->getDesc() . '" />
            <input type="hidden" class="hidden_type" name="items[' . $item->getId() . '][type]" value="' . $item->getType() . '" />
            <input type="hidden" class="hidden_prefixes" name="items[' . $item->getId() . '][prefixes]" value="' . $item->getPrefixes() . '" />
            <input type="hidden" class="hidden_read_access" name="items[' . $item->getId() . '][readAccess]" value="' . $item->getReadAccess() . '" />
            <input type="hidden" class="hidden_reply_access" name="items[' . $item->getId() . '][replyAccess]" value="' . $item->getReplyAccess() . '" />
            <input type="hidden" class="hidden_create_access" name="items[' . $item->getId() . '][createAccess]" value="' . $item->getCreateAccess() . '" />
            <span></span>
        </span>
        <span class="title">' . $item->getTitle() . '</span>
        <span class="item_delete">
            <i class="fa-solid fa-circle-xmark"></i>
        </span><span class="item_edit">
            <i class="fa-regular fa-pen-to-square"></i>
        </span>
    </div>';

    if (!empty($item->getSubItems())) {
        echo '<ol>';

        foreach ($item->getSubItems() as $subItem) {
            rec($subItem, $obj);
        }

        echo '</ol>';
    }

    echo '</li>';
}
?>

<link rel="stylesheet" href="<?=$this->getModuleUrl('static/css/forum-items.css') ?>">

<form class="row" id="forumForm" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <h1><?=$this->getTrans('forum') ?></h1>
    <div class="col-xl-6">
        <ol id="sortable" class="sortable">
            <?php
            foreach ($forumItems as $item) {
                rec($item, $this);
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
                    <option value="1"><?=$this->getTrans('forum') ?></option>
                </select>
            </div>
        </div>
        <div class="dyn"></div>
        <div class="row mb-3">
            <label class="col-xl-3 col-form-label"></label>
            <div class="col-xl-6 actions">
                <input type="button" class="btn btn-outline-secondary" id="menuItemAdd" value="<?=$this->getTrans('forumItemAdd') ?>">
            </div>
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar() ?>
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

        let choicesAssignedPrefixes;
        let choicesAssignedGroupsRead;
        let choicesAssignedGroupsReply;
        let choicesAssignedGroupsCreate;

        $('.disclose').on('click', function () {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
            $(this).find('i').toggleClass('fa-minus-circle').toggleClass('fa-plus-circle');
        });
        $('#forumForm').submit (
            function () {
                $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
            }
        );
        $('.sortable').on('click', '.item_delete', function() {
            $(this).closest('li').remove();
        });

        $('#forumForm').on('change', '#type', function() {
            var options = '';

            $('#sortable').find('li').each(function() {
                if ($(this).find('input.hidden_type:first').val() == 0) {
                    options += '<option value="'+$(this).find('input.hidden_id:first').val()+'">'+$(this).find('input.hidden_title:first').val()+'</option>';
                }
            });

            if (options == '' && ($(this).val() == '1')) {
                alert('<?=$this->getTrans('infoCat') ?>');
                $(this).val(0);
                return;
            }

            menuHtml = '<div class="row mb-3"><label for="menukey" class="col-xl-3 col-form-label"><?=$this->getTrans('menuSelection') ?></label>\n\
                        <div class="col-xl-6"><select class="form-select" id="menukey">'+options+'</select></div></div>\n\
                        <div class="row mb-3"><label for="assignedPrefixes" class="col-xl-3 col-form-label"><?=$this->getTrans('prefixes') ?></label>\n\
                        <div class="col-xl-6"><select class="choices-select form-control" id="assignedPrefixes" data-placeholder="<?=$this->getTrans('selectPrefixes') ?>" multiple>\n\
                        \n\
                        <?php
                        /** @var \Modules\Forum\Models\Prefix $prefix */
                        foreach ($this->get('prefixes') ?? [] as $prefix) : ?>\n\
                        <option value="<?=$prefix->getId() ?>"><?=$this->escape($prefix->getPrefix()) ?></option>\n\
                        <?php endforeach; ?>\n\
                        </select></div></div>\n\
                        <div class="row mb-3"><label for="assignedGroupsRead" class="col-xl-3 col-form-label"><?=$this->getTrans('see') ?></label>\n\
                        <div class="col-xl-6"><select class="choices-select form-control" id="assignedGroupsRead" name="user[groups][]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>\n\
                        \n\
                        <?php
                        /** @var \Modules\User\Models\Group $group */
                        foreach ($this->get('userGroupList') as $group) : ?>\n\
                        <option value="<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></option>\n\
                        <?php endforeach; ?>\n\
                        </select></div></div>\n\
                        <div class="row mb-3"><label for="assignedGroupsReply" class="col-xl-3 col-form-label"><?=$this->getTrans('answer') ?></label>\n\
                        <div class="col-xl-6"><select class="choices-select form-control" id="assignedGroupsReply" name="user[groups][]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>\n\
                        \n\
                        <?php
                        /** @var \Modules\User\Models\Group $group */
                        foreach ($this->get('userGroupList') as $group) : ?>\n\
                        <option value="<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></option>\n\
                        <?php endforeach; ?>\n\
                        </select></div></div>\n\
                        <div class="row mb-3"><label for="assignedGroupsCreate" class="col-xl-3 col-form-label"><?=$this->getTrans('create') ?></label>\n\
                        <div class="col-xl-6"><select class="choices-select form-control" id="assignedGroupsCreate" name="user[groups][]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>\n\
                        \n\
                        <?php
                        /** @var \Modules\User\Models\Group $group */
                        foreach ($this->get('userGroupList') as $group) : ?>\n\
                        <option value="<?=$group->getId() ?>"><?=$this->escape($group->getName()) ?></option>\n\
                        <?php endforeach; ?>\n\
                        </select></div></div>';

            if ($(this).val() == '0') {
                $('.dyn').html('');
            } else if ($(this).val() == '1') {
                $('.dyn').html(menuHtml);

                choicesAssignedPrefixes = new Choices('#assignedPrefixes', {
                    ...choicesOptions,
                    searchEnabled: true
                });
                choicesAssignedGroupsRead = new Choices('#assignedGroupsRead', {
                    ...choicesOptions,
                    searchEnabled: true
                });
                choicesAssignedGroupsReply = new Choices('#assignedGroupsReply', {
                    ...choicesOptions,
                    searchEnabled: true
                });
                choicesAssignedGroupsCreate = new Choices('#assignedGroupsCreate', {
                    ...choicesOptions,
                    searchEnabled: true
                });
            }
        });

        $('#forumForm').on('click', '#menuItemAdd', function () {
            if ($('#title').val() == '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
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

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                +'<input type="hidden" class="hidden_id" name="items[tmp_'+itemId+'][id]" value="tmp_'+itemId+'" />'
                +'<input type="hidden" class="hidden_title" name="items[tmp_'+itemId+'][title]" value="'+$('#title').val()+'" />'
                +'<input type="hidden" class="hidden_desc" name="items[tmp_'+itemId+'][desc]" value="'+$('#desc').val()+'" />'
                +'<input type="hidden" class="hidden_type" name="items[tmp_'+itemId+'][type]" value="'+$('#type').val()+'" />'
                +'<input type="hidden" class="hidden_prefixes" name="items[tmp_'+itemId+'][prefixes]" value="'+$('#assignedPrefixes').val()+'" />'
                +'<input type="hidden" class="hidden_read_access" name="items[tmp_'+itemId+'][readAccess]" value="'+$('#assignedGroupsRead').val()+'" />'
                +'<input type="hidden" class="hidden_reply_access" name="items[tmp_'+itemId+'][replyAccess]" value="'+$('#assignedGroupsReply').val()+'" />'
                +'<input type="hidden" class="hidden_create_access" name="items[tmp_'+itemId+'][createAccess]" value="'+$('#assignedGroupsCreate').val()+'" />'
                +'</span></span><span class="title">'+$('#title').val()+'</span><span class="item_delete"><i class="fa-solid fa-circle-xmark"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
        });

        $('.sortable').on('click', '.item_edit', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemEdit" value="<?=$this->getTrans('edit') ?>">\n\
                                <input type="button" class="btn" id="menuItemEditCancel" value="<?=$this->getTrans('cancel') ?>">');
            $('#title').val($(this).parent().find('.hidden_title').val());
            $('#desc').val($(this).parent().find('.hidden_desc').val());
            $('#type').val($(this).parent().find('.hidden_type').val());
            $('#type').change();

            let hidden_prefixes_Element = $('#assignedPrefixes');
            let hidden_prefixes_values = $(this).parent().find('.hidden_prefixes').val().split(',').map(value => value.trim());
            hidden_prefixes_Element.val(null).trigger('change');
            hidden_prefixes_Element.val(hidden_prefixes_values).trigger('change');
            choicesAssignedPrefixes.setValue(hidden_prefixes_values);

            hidden_prefixes_values.forEach(value => {
                choicesAssignedPrefixes.setChoiceByValue(value);
            });

            let hidden_read_access_Element = $('#assignedGroupsRead');
            let hidden_read_access_values = $(this).parent().find('.hidden_read_access').val().split(',').map(value => value.trim());
            hidden_read_access_Element.val(null).trigger('change');
            hidden_read_access_Element.val(hidden_read_access_values).trigger('change');
            choicesAssignedGroupsRead.setValue(hidden_read_access_values);

            hidden_read_access_values.forEach(value => {
                choicesAssignedGroupsRead.setChoiceByValue(value);
            });

            let hidden_reply_access_Element = $('#assignedGroupsReply');
            let hidden_reply_access_values = $(this).parent().find('.hidden_reply_access').val().split(',').map(value => value.trim());
            hidden_reply_access_Element.val(null).trigger('change');
            hidden_reply_access_Element.val(hidden_reply_access_values).trigger('change');
            choicesAssignedGroupsReply.setValue(hidden_reply_access_values);

            hidden_reply_access_values.forEach(value => {
                choicesAssignedGroupsReply.setChoiceByValue(value);
            });

            let hidden_create_access_Element = $('#assignedGroupsCreate');
            let hidden_create_access_values = $(this).parent().find('.hidden_create_access').val().split(',').map(value => value.trim());
            hidden_create_access_Element.val(null).trigger('change');
            hidden_create_access_Element.val(hidden_create_access_values).trigger('change');
            choicesAssignedGroupsCreate.setValue(hidden_create_access_values);

            hidden_create_access_values.forEach(value => {
                choicesAssignedGroupsCreate.setChoiceByValue(value);
            });

            $('#id').val($(this).closest('li').attr('id'));
        });

        $('#forumForm').on('click', '#menuItemEdit', function () {
            if ($('#title').val() == '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                return;
            }

            let idSelector = $('#'+$('#id').val());
            idSelector.find('.title:first').text($('#title').val());
            idSelector.find('.hidden_title:first').val($('#title').val());
            idSelector.find('.hidden_desc:first').val($('#desc').val());
            idSelector.find('.hidden_type:first').val($('#type').val());
            idSelector.find('.hidden_prefixes:first').val($('#assignedPrefixes').val());
            idSelector.find('.hidden_read_access:first').val($('#assignedGroupsRead').val());
            idSelector.find('.hidden_reply_access:first').val($('#assignedGroupsReply').val());
            idSelector.find('.hidden_create_access:first').val($('#assignedGroupsCreate').val());
            resetBox();
        });

        $('#forumForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemAdd" value="<?=$this->getTrans('forumItemAdd') ?>">');
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
