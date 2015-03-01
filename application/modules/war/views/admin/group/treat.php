<?php if($this->getRequest()->getParam('id') == ''){ ?>
    <legend><?=$this->getTrans('manageNewGroup'); ?></legend>
<?php }  else { ?>
    <legend><?=$this->getTrans('treatGroup'); ?></legend>
<?php } ?>
<?php 
if ($this->get('groups') != '') {
    $str = $this->escape($this->get('groups')->getGroupMember());
    $memberArray =  explode(" ", $str);
}
?>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="form-group">
        <label for="groupNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupName'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="groupName"
                   value="<?php if ($this->get('groups') != '') { echo $this->escape($this->get('groups')->getGroupName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="groupTagInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupTag'); ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="groupTag"
                   value="<?php if ($this->get('groups') != '') { echo $this->escape($this->get('groups')->getGroupTag()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="groupImage"
                class="col-lg-2 control-label">
            <?=$this->getTrans('groupImage'); ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input class="form-control"
                       type="text"
                       name="groupImage"
                       id="selectedImage"
                       placeholder="<?=$this->getTrans('groupImage'); ?>"
                       value="<?php if ($this->get('groups') != '') { echo $this->escape($this->get('groups')->getGroupImage()); } ?>" />
                <span class="input-group-addon"><a id="media" href="#"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="assignedMember" class="col-lg-2 control-label">
                <?=$this->getTrans('assignedMember'); ?>
        </label>
        <div class="col-lg-8">
            <select class="form-control" name="userGroup" id="warGroup">
                <optgroup label="<?=$this->getTrans('groupsName'); ?>">
                <?php
                    foreach ($this->get('userGroupList') as $groupList) {
                        $selected = '';

                        if($this->get('groups') != ''){
                            if ($this->escape($this->get('groups')->getGroupMember()) == $groupList->getId()) {
                                    $selected = 'selected="selected"';
                            }
                        }

                        echo '<option '.$selected.' value="'.$groupList->getId().'">'.$this->escape($groupList->getName()).'</option>';
                    }
                ?>
                </optgroup>
            </select>
        </div>
    </div>
    <?php
    if ($this->get('groups') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
<script>
$('#media').click
(
    function()
    {
        $('#MediaModal').modal('show');

        var src = iframeSingleUrlImage;
        var height = '100%';
        var width = '100%';

        $("#MediaModal iframe").attr
        (
            {
                'src': src,
                'height': height,
                'width': width
            }
        );
    }
);

$('#assignedMember').chosen();
$('#assignedMember_chosen').css('width', '100%'); // Workaround for chosen resize bug.
</script>
