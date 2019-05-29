<?php
$rules = $this->get('rules');
$rulesparents = $this->get('rulesparents');

function rec($item, $obj)
{
    $parentItem = null;
    $subItems = $obj->get('rulesMapper')->getRulesItemsByParent($item->getId());
    $class = 'mjs-nestedSortable-branch mjs-nestedSortable-expanded';

    if ($item->getParent_Id() != 0) {
        $parentItem = $obj->get('rulesMapper')->getRuleById($item->getParent_Id());
    }

    if (empty($subItems)) {
        $class = 'mjs-nestedSortable-leaf';
    }

    echo '<li id="list_'.$item->getId().'" class="'.$class.'">';
    echo '<div><span class="disclose"><i class="fa fa-minus-circle"></i>
                    <input type="hidden" class="hidden_id" name="items['.$item->getId().'][id]" value="'.$item->getId().'" />
                    <input type="hidden" class="hidden_paragraph" name="items['.$item->getId().'][paragraph]" value="'.$item->getParagraph().'" />
                    <input type="hidden" class="hidden_title" name="items['.$item->getId().'][title]" value="'.$item->getTitle().'" />
                    <input type="hidden" class="hidden_text" name="items['.$item->getId().'][text]" value="'.$item->getText().'" />
                    <input type="hidden" class="hidden_parent" name="items['.$item->getId().'][parent]" value="'.$item->getParent_Id().'" />
                    <input type="hidden" class="hidden_parentparagraph" name="items['.$item->getId().'][parentparagraph]" value="'.($parentItem != ''?$parentItem->getParagraph():0).'" />
                    <input type="hidden" class="hidden_access" name="items['.$item->getId().'][access]" value="'.$item->getAccess().'" />
                    <span></span>
                </span>
                <span class="title">'.$obj->getTrans('art').' '.$obj->escape(($parentItem != ''?$parentItem->getParagraph().' '.$obj->getTrans('paragraphsign').' ':'').$item->getParagraph()).' : '.$item->getTitle().'</span>
                <span class="item_delete">
                    <i class="fa fa-times-circle"></i>
                </span><span class="item_edit">
                    <i class="fa fa-edit"></i>
                </span>
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

<form class="form-horizontal" id="rulesForm" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <h1><?=$this->getTrans('manage') ?></h1>
    <div class="col-lg-6">
        <ol id="sortable" class="sortable">
            <?php
                if (!empty($rules)) {
                    foreach ($rules as $item) {
                        rec($item, $this);
                    }
                }
            ?>
        </ol>
    </div>
    <div class="col-lg-6 changeBox">
        <input type="hidden" id="id" value="" />
        <input type="hidden" id="parentparagraph" value="" />
        <div class="form-group">
            <label for="paragraph" class="col-lg-3 control-label">
                <?=$this->getTrans('paragraph') ?>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="paragraph" />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-lg-3 control-label">
                <?=$this->getTrans('title') ?>
            </label>
            <div class="col-lg-6">
                <input type="text" class="form-control" id="title" />
            </div>
        </div>
        <div class="form-group">
            <label for="parent" class="col-lg-3 control-label">
                <?=$this->getTrans('cat') ?>
            </label>
            <div class="col-lg-6">
                <select class="form-control" id="parent">
                    <option value="0"><?=$this->getTrans('new') ?></option>
                    <?php foreach ($rulesparents as $item): ?>
                    <option value="<?=$item->getId() ?>"><?=$item->getParagraph().'. '.$item->getTitle() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="dyn"></div>
        <div class="form-group">
            <label for="assignedGroupsRead" class="col-lg-3 control-label">
                <?=$this->getTrans('see') ?>
            </label>
            <div class="col-lg-6">
                <select class="chosen-select form-control" id="assignedGroupsRead" name="user[groups][]" data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>" multiple>
                    <?php foreach ($this->get('userGroupList') as $groupList): ?>
                    <option value="<?=$groupList->getId() ?>"><?=$this->escape($groupList->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-lg-offset-3 actions">
            <input type="button" class="btn" id="menuItemAdd" value="<?=$this->getTrans('create') ?>">
        </div>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar('saveButton') ?>
</form>

<script>
    $('#assignedGroupsRead').chosen();

    function resetBox() {
        $(':input','.changeBox')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        $('#id').val('');
        $('#parentparagraph').val('');

        $("#parent option").prop('disabled', false);
        $("#parent option[value='0']").text('<?=$this->getTrans('new') ?>');
        $('#parent').val(0);
        $('#parent').change();


        $('#assignedGroupsRead option').each(function(){
            $(this)[0].selected = false;   
        });
        $('#assignedGroupsRead').trigger("chosen:updated");
    }

    $(document).ready (function () {
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
        $('#rulesForm').submit (function () {
            $('#hiddenMenu').val(JSON.stringify($('.sortable').nestedSortable('toArray', {startDepthCount: 0})));
        });
        $('.sortable').on('click', '.item_delete', function() {
            $(this).closest('li').remove();
        });

        $('#rulesForm').on('change', '#parent', function() {
            var text = '';
            if ($('#id').val() != '') {
                text = $('#'+$('#id').val()).find('.hidden_text:first').val();
            }

            menuHtml = '<div class="form-group"><label for="text" class="col-lg-3 control-label"><?=$this->getTrans('text') ?></label>\n\
                <div class="col-lg-6"><textarea class="form-control ckeditor"\n\
                id="text"\n\
                name="text"\n\
                rows="3"\n\
                cols="45"\n\
                toolbar="ilch_html">'+text+'</textarea></div></div>';

            if ($(this).val() == '0') {
                if (CKEDITOR.instances.text) {
                    CKEDITOR.instances.text.destroy();
                }
                $('.dyn').html('');
                $('#parentparagraph').val('');
            } else {
                $('.dyn').html(menuHtml);

                // if there is an existing instance of this editor
                if (CKEDITOR.instances.text) {
                    /* destroying instance */
                    CKEDITOR.instances.text.destroy();
                    CKEDITOR.replace('text');
                } else {
                    /* re-creating instance */
                    CKEDITOR.replace('text');
                }

                var paragraph = $('#list_'+$(this).val()).find('.hidden_paragraph:first').val();
                $('#parentparagraph').val(paragraph);
            }
        });

        $('#rulesForm').on('click', '#menuItemAdd', function () {
            if ($('#title').val() == '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                return;
            }

            if ($('#paragraph').val() == '') {
                alert(<?=json_encode($this->getTrans('missingParagraph')) ?>);
                return;
            }

            append = '#sortable';

            if ($('#parent').val() != 0 ) {
                id = $('#parent').val();

                if ($('#sortable #'+id+' ol').length <= 0) {
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

            if (CKEDITOR.instances.text) {
                var textvalue = CKEDITOR.instances['text'].getData();
            } else {
                var textvalue = $('#text').val()
            }

            $('<li id="tmp_'+itemId+'"><div><span class="disclose"><span>'
                +'<input type="hidden" class="hidden_id" name="items[tmp_'+itemId+'][id]" value="tmp_'+itemId+'" />'
                +'<input type="hidden" class="hidden_paragraph" name="items[tmp_'+itemId+'][paragraph]" value="'+$('#paragraph').val()+'" />'
                +'<input type="hidden" class="hidden_parentparagraph" name="items[tmp_'+itemId+'][parentparagraph]" value="'+$('#parentparagraph').val()+'" />'
                +'<input type="hidden" class="hidden_title" name="items[tmp_'+itemId+'][title]" value="'+$('#title').val()+'" />'
                +'<input type="hidden" class="hidden_text" name="items[tmp_'+itemId+'][text]" value="'+textvalue+'" />'
                +'<input type="hidden" class="hidden_parent" name="items[tmp_'+itemId+'][parent]" value="'+$('#parent').val()+'" />'
                +'<input type="hidden" class="hidden_access" name="items[tmp_'+itemId+'][access]" value="'+$('#assignedGroupsRead').val()+'" />'
                +'</span></span><span class="title">'+$('<div/>').html('<?=$this->getTrans('art')?> ').text()+($('#parentparagraph').val() != 0?$('#parentparagraph').val()+$('<div/>').html(' <?=$this->getTrans('paragraphsign')?> ').text():'')+$('#paragraph').val()+' : '+$('#title').val()+'</span><span class="item_delete"><i class="fa fa-times-circle"></i></span></div></li>').appendTo(append);
            itemId++;
            resetBox();
        });

        $('.sortable').on('click', '.item_edit', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemEdit" value="<?=$this->getTrans('edit') ?>">\n\
            <input type="button" class="btn" id="menuItemEditCancel" value="<?=$this->getTrans('cancel') ?>">');
            $('#id').val($(this).closest('li').attr('id'));
            $('#paragraph').val($(this).parent().find('.hidden_paragraph').val());

            $('#parent').val($(this).parent().find('.hidden_parent').val());

            if ($(this).parent().find('.hidden_parent').val() == null || $(this).parent().find('.hidden_parent').val() === undefined || $(this).parent().find('.hidden_parent').val() == 0) {
                $("#parent option").prop('disabled', true);
                $("#parent option[value='0']").text('<?=$this->getTrans('change') ?>');
                $("#parent option[value='0']").prop('disabled', false);
            } else {
                $("#parent option").prop('disabled', false);
                $("#parent option[value='0']").text('<?=$this->getTrans('new') ?>');
                $("#parent option[value='0']").prop('disabled', true);
            }
            $('#parent').change();

            $('#parentparagraph').val($(this).parent().find('.hidden_parentparagraph').val());
            $('#title').val($(this).parent().find('.hidden_title').val());
            $('#assignedGroupsRead option').each(function(){
                $(this)[0].selected = false;   
            });
            $.each($(this).parent().find('.hidden_access').val().split(","), function(index, element) {
                if (element) {
                    $('#assignedGroupsRead > option[value=' + element + ']').prop("selected", true);
                }
            });
            $('#assignedGroupsRead').trigger("chosen:updated");
        });

        $('#rulesForm').on('click', '#menuItemEdit', function () {
            if ($('#title').val() == '') {
                alert(<?=json_encode($this->getTrans('missingTitle')) ?>);
                return;
            }
            if ($('#paragraph').val() == '') {
                alert(<?=json_encode($this->getTrans('missingParagraph')) ?>);
                return;
            }

            $('#'+$('#id').val()).find('.title:first').text($('<div/>').html('<?=$this->getTrans('art')?> ').text()+($('#parentparagraph').val() != 0?$('#parentparagraph').val()+$('<div/>').html(' <?=$this->getTrans('paragraphsign')?> ').text():'')+$('#paragraph').val()+' : '+$('#title').val());
            $('#'+$('#id').val()).find('.hidden_paragraph:first').val($('#paragraph').val());
            if ($('#parent').val() != 0 ) {
                $('#'+$('#id').val()).find('.hidden_parentparagraph:first').val($('#parentparagraph').val());
                if (CKEDITOR.instances.text) {
                    var textvalue = CKEDITOR.instances['text'].getData();
                } else {
                    var textvalue = $('#text').val()
                }
                $('#'+$('#id').val()).find('.hidden_text:first').val(textvalue);
            } else {
                $('#'+$('#id').val()).find('.hidden_parentparagraph:first').val(0);
                $('#'+$('#id').val()).find('.hidden_text:first').val('');
            }
            $('#'+$('#id').val()).find('.hidden_title:first').val($('#title').val());
            if (CKEDITOR.instances.text) {
                var textvalue = CKEDITOR.instances['text'].getData();
            } else {
                var textvalue = $('#text').val()
            }
            $('#'+$('#id').val()).find('.hidden_text:first').val(textvalue);
            $('#'+$('#id').val()).find('.hidden_parent:first').val($('#parent').val());
            $('#'+$('#id').val()).find('.hidden_access:first').val($('#assignedGroupsRead').val());
            resetBox();
        });

        $('#rulesForm').on('click', '#menuItemEditCancel', function() {
            $('.actions').html('<input type="button" class="btn" id="menuItemAdd" value="<?=$this->getTrans('create') ?>">');
            resetBox();
        });
    });
</script>
<script>
function reload() {
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
