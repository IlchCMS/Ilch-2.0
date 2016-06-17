<link href="<?=$this->getModuleUrl('static/css/forum-style.css') ?>" rel="stylesheet">
<?php
$forumItems = $this->get('forumItems');
$readAccess = $this->get('groupIdsArray');
$edittopicitems = $this->get('edittopicitems');

function rec($item, $obj, $readAccess, $i)
{
    $subItems = $item->getSubItems();
    $adminAccess = null;
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
    $subItemsFalse = false;
    if ($item->getType() === 0) {
        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                if (is_in_array($readAccess, explode(',', $subItem->getReadAccess())) || $adminAccess == true) {
                     $subItemsFalse = true;
                }
            }
        }
    }
?>
    <?php if ($item->getType() === 0 && $subItemsFalse == true): ?>
        <optgroup label="<?=$item->getTitle() ?>"></optgroup>
    <?php endif; ?>

    <?php if (is_in_array($readAccess, explode(',', $item->getReadAccess())) || $adminAccess == true): ?>
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
<div id="forum" class="col-lg-12">
    <?php if (!empty($forumItems)): ?>
        <form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => 'edittopic']) ?>">
            <?php echo $this->getTokenField(); ?>
            <select class="form-control" name="edit">
                <?php foreach ($forumItems as $item): ?>
                    <?php rec($item, $this, $readAccess, $i = null) ?>
                <?php endforeach; ?>
            </select>
            <?php foreach ($edittopicitems as $editid): ?>
                <input type="hidden" name="topicids[]" value="<?=$editid ?>">
            <?php endforeach; ?>
            <button value="edittopic" type="submit" name="edittopic" class="btn">
                <?=$this->getTrans('edit') ?>
            </button>
        </form>
    <?php endif; ?>
</div>
