<?php
if ($this->get('groups') != '') {
    $str = $this->get('groups')->getGroupMember();
    $memberArray =  explode(" ", $str);
}
?>

<h1><?=($this->getRequest()->getParam('id') == '') ? $this->getTrans('manageNewGroup') : $this->getTrans('treatGroup') ?></h1>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=in_array('groupName', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="groupNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="groupNameInput"
                   name="groupName"
                   value="<?=$this->escape(($this->get('groups') != '') ? $this->get('groups')->getGroupName() : $this->get('post')['groupName']) ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('groupTag', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="groupTagInput" class="col-lg-2 control-label">
            <?=$this->getTrans('groupTag') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="groupTagInput"
                   name="groupTag"
                   value="<?=$this->escape(($this->get('groups') != '') ? $this->get('groups')->getGroupTag() : $this->get('post')['groupTag']) ?>" />
        </div>
    </div>
    <div class="form-group<?=in_array('groupImage', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="selectedImage_1" class="col-lg-2 control-label">
            <?=$this->getTrans('groupImage') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="groupImage"
                       placeholder="<?=$this->getTrans('groupImage') ?>"
                       value="<?=$this->escape(($this->get('groups') != '') ? $this->get('groups')->getGroupImage() : $this->get('post')['groupImage']) ?>" />
                <span class="input-group-addon">
                    <a id="media" href="javascript:media_1()"><i class="fa fa-picture-o"></i></a>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="groupDesc" class="col-lg-2 control-label">
            <?=$this->getTrans('groupDesc') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <textarea class="form-control"
                          name="groupDesc"
                          cols="50"
                          rows="5"
                          placeholder="<?=$this->getTrans('groupDesc') ?>"><?=$this->escape(($this->get('groups') != '') ? $this->get('groups')->getGroupDesc() : '') ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group<?=in_array('userGroup', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="warGroup" class="col-lg-2 control-label">
            <?=$this->getTrans('assignedMember') ?>
        </label>
        <div class="col-lg-8">
            <select class="form-control" id="warGroup" name="userGroup">
                <optgroup label="<?=$this->getTrans('groupsName') ?>">
                    <?php foreach ($this->get('userGroupList') as $groupList): ?>
                        <?php
                        $selected = '';
                        if ($this->get('groups') != '') {
                            if ($this->get('groups')->getGroupMember() == $groupList->getId()) {
                                $selected = ' selected="selected"';
                            }
                        }
                        ?>
                        <?php if ($groupList->getId() != '3'): ?>
                            <option<?=$selected ?> value="<?=$groupList->getId() ?>"><?=$this->escape($groupList->getName()) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <?=($this->get('groups') != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script>
    // Example for multiple input filds
    <?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
    ?>

    $('#assignedMember').chosen();
</script>
