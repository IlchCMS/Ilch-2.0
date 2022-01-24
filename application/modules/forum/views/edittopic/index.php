<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">
<?php
$forumItems = $this->get('forumItems');
$readAccess = $this->get('groupIdsArray');
$editTopicItems = $this->get('editTopicItems');

function rec($item, $obj, $readAccess, $i)
{
    $subItems = $item->getSubItems();
    $adminAccess = null;
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
    $subItemsFalse = false;
    if (!empty($subItems) && ($item->getType() === 0)) {
        foreach ($subItems as $subItem) {
            if ($adminAccess == true || is_in_array($readAccess, explode(',', $subItem->getReadAccess()))) {
                $subItemsFalse = true;
            }
        }
    } ?>
    <?php if ($subItemsFalse == true && $item->getType() === 0): ?>
        <optgroup label="<?=$item->getTitle() ?>"></optgroup>
    <?php endif; ?>

    <?php if ($adminAccess == true || is_in_array($readAccess, explode(',', $item->getReadAccess()))): ?>
        <?php if ($item->getType() != 0): ?>
            <?php $selected = ''; ?>
            <?php if ($item->getId() == $obj->getRequest()->getParam('forumid')): ?>
                <?php $selected = 'selected="selected"'; ?>
            <?php endif; ?>
                <option value="<?=$item->getId() ?>" <?=$selected?>><?=$item->getTitle() ?></option>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    if (!empty($subItems) && $i == 0) {
        $i++;
        foreach ($subItems as $subItem) {
            rec($subItem, $obj, $readAccess, $i);
        }
    }
}
?>

<div id="forum">
    <div class="row">
        <div class="col-lg-12">
            <div class="new-post-head ilch-head">
                <?=$this->getTrans('topicMoveTo') ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="new-topic ilch-bg ilch-border">
                <form class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <div class="form-group">
                        <label for="selectForum" class="col-lg-2 control-label">
                            <?=$this->getTrans('selectForum') ?>
                        </label>
                        <div class="col-lg-6">
                            <select class="form-control" id="selectForum" name="edit">
                                <?php foreach ($forumItems as $item): ?>
                                    <?php rec($item, $this, $readAccess, $i = null) ?>
                                <?php endforeach; ?>
                            </select>
                            <?php foreach ($editTopicItems as $editId): ?>
                                <input type="hidden" name="topicids[]" value="<?=$editId ?>" />
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-primary" name="edittopic" value="edittopic">
                                <?=$this->getTrans('move') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
