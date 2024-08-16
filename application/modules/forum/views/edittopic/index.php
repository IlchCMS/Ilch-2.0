<?php

/** @var View $this */

use Ilch\View;
use Modules\Forum\Models\ForumItem;

?>
<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">
<?php
/** @var ForumItem[]|null $forumItems */
$forumItems = $this->get('forumItems');
/** @var int[] $editTopicItems */
$editTopicItems = $this->get('editTopicItems');

/**
 * @param ForumItem $item
 * @param View $obj
 * @param int|null $i
 */
function rec(ForumItem $item, View $obj, ?int $i)
{
    $subItems = $item->getSubItems();
    $adminAccess = null;
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
    $subItemsFalse = false;
    if (!empty($subItems) && ($item->getType() === 0)) {
        foreach ($subItems as $subItem) {
            if ($adminAccess || $subItem->getReadAccess()) {
                $subItemsFalse = true;
            }
        }
    } ?>
    <?php if ($subItemsFalse && $item->getType() === 0) : ?>
        <optgroup label="<?=$item->getTitle() ?>"></optgroup>
    <?php endif; ?>

    <?php if ($adminAccess || $item->getReadAccess()) : ?>
        <?php if ($item->getType() != 0) : ?>
            <?php $selected = ''; ?>
            <?php if ($item->getId() == $obj->getRequest()->getParam('forumid')) : ?>
                <?php $selected = 'selected="selected"'; ?>
            <?php endif; ?>
                <option value="<?=$item->getId() ?>" <?=$selected?>><?=$item->getTitle() ?></option>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    if (!empty($subItems) && $i == 0) {
        $i++;
        foreach ($subItems as $subItem) {
            rec($subItem, $obj, $i);
        }
    }
}
?>

<div id="forum">
    <div class="row">
        <div class="col-xl-12">
            <div class="new-post-head ilch-head">
                <?=$this->getTrans('topicMoveTo') ?>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="new-topic ilch-bg ilch-border">
                <form method="POST">
                    <?=$this->getTokenField() ?>
                    <div class="row mb-3">
                        <label for="selectForum" class="col-xl-2 col-form-label">
                            <?=$this->getTrans('selectForum') ?>
                        </label>
                        <div class="col-xl-6">
                            <select class="form-select" id="selectForum" name="edit">
                                <?php foreach ($forumItems as $item) : ?>
                                    <?php rec($item, $this, $i = null) ?>
                                <?php endforeach; ?>
                            </select>
                            <?php foreach ($editTopicItems as $editId) : ?>
                                <input type="hidden" name="topicids[]" value="<?=$editId ?>" />
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="offset-xl-2 col-xl-10">
                            <button type="submit" class="btn btn-sm btn-primary" name="edittopic" value="edittopic">
                                <?=$this->getTrans('move') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
