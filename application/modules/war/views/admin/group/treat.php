<?php if ($this->get('groups') != ''): ?>
    <?php $str = $this->get('groups')->getGroupMember() ?>
    <?php $memberArray =  explode(" ", $str) ?>
<?php endif; ?>

<legend>
    <?php if ($this->getRequest()->getParam('id') == ''): ?>
        <?=$this->getTrans('manageNewGroup') ?>
    <?php else: ?>
        <?=$this->getTrans('treatGroup') ?>
    <?php endif; ?>
</legend>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="form-group">
        <label for="groupNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupName') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="groupName"
                   value="<?php if ($this->get('groups') != '') { echo $this->get('groups')->getGroupName(); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="groupTagInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupTag') ?>:
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="groupTag"
                   value="<?php if ($this->get('groups') != '') { echo $this->get('groups')->getGroupTag(); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="groupImage" class="col-lg-2 control-label">
            <?=$this->getTrans('groupImage') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input class="form-control"
                       type="text"
                       name="groupImage"
                       id="selectedImage_1"
                       placeholder="<?=$this->getTrans('groupImage') ?>"
                       value="<?php if ($this->get('groups') != '') { echo $this->get('groups')->getGroupImage(); } ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media_1()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="assignedMember" class="col-lg-2 control-label">
                <?=$this->getTrans('assignedMember') ?>
        </label>
        <div class="col-lg-8">
            <select class="form-control" name="userGroup" id="warGroup">
                <optgroup label="<?=$this->getTrans('groupsName') ?>">
                    <?php foreach ($this->get('userGroupList') as $groupList): ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('groups') != ''): ?>
                            <?php if ($this->get('groups')->getGroupMember() == $groupList->getId()): ?>
                                <?php $selected = 'selected="selected"'; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <option <?=$selected ?> value="<?=$groupList->getId() ?>"><?=$groupList->getName() ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <?php if ($this->get('groups') != ''): ?>
        <?=$this->getSaveBar('updateButton') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButton') ?>
    <?php endif; ?>
</form>

<script>
// Example for multiple input filds
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1') ?>

$('#assignedMember').chosen();
$('#assignedMember_chosen').css('width', '100%'); // Workaround for chosen resize bug.
</script>
