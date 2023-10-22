<h1><?=$this->getTrans('settings') ?>
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info" ></i>
    </a>
</h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('reply') ? 'has-error' : '' ?>">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('acceptReply') ?>:
        </div>
        <div class="col-xl-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="reply-yes" name="reply" value="1" <?php if ($this->get('comment_reply') == '1') { echo 'checked="checked"'; } ?> />
                <label for="reply-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="reply-no" name="reply" value="0" <?php if ($this->get('comment_reply') != '1') { echo 'checked="checked"'; } ?> />
                <label for="reply-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
         </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('nesting') ? 'has-error' : '' ?>">
        <label for="nesting" class="col-xl-2 control-label">
            <?=$this->getTrans('nesting') ?>:
        </label>
        <div class="col-xl-1">
            <input class="form-control"
                   type="number"
                   id="nesting"
                   name="nesting"
                   min="0"
                   value="<?=$this->get('comment_nesting') ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label for="floodIntervalInput" class="col-xl-2 control-label">
            <?=$this->getTrans('floodInterval') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="floodIntervalInput"
                   name="floodInterval"
                   min="0"
                   value="<?=$this->escape($this->get('floodInterval')) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="excludeFloodProtection" class="col-xl-2 control-label">
            <?=$this->getTrans('excludeFloodProtection') ?>:
        </label>
        <div class="col-xl-4">
            <select class="chosen-select form-control"
                    id="excludeFloodProtection"
                    name="groups[]"
                    data-placeholder="<?=$this->getTrans('excludeFloodProtection') ?>"
                    multiple>
                <?php
                foreach ($this->get('groupList') as $group) {
                    ?>
                    <option value="<?=$group->getId() ?>"
                        <?php
                        foreach ($this->get('excludeFloodProtection') as $assignedGroup) {
                            if ($group->getId() == $assignedGroup) {
                                echo 'selected="selected"';
                                break;
                            }
                        } ?>>
                        <?=$this->escape($group->getName()) ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <h2><?=$this->getTrans('boxSettings') ?></h2>
    <div class="row mb-3">
        <label for="boxCommentsLimit" class="col-xl-2 control-label">
            <?=$this->getTrans('boxCommentsLimit') ?>
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="boxCommentsLimit"
                   name="boxCommentsLimit"
                   min="1"
                   value="<?=($this->get('boxCommentsLimit') != '') ? $this->escape($this->get('boxCommentsLimit')) : 5 ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('CommentCommentInfoText')) ?>

<script>
    $('#excludeFloodProtection').chosen();
</script>
