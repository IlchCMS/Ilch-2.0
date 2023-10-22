<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="threadsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('threadsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="threadsPerPageInput"
                   name="threadsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('threadsPerPage')) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="postsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('postsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="postsPerPageInput"
                   name="postsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('postsPerPage')) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="floodIntervalInput" class="col-lg-2 control-label">
            <?=$this->getTrans('floodInterval') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="floodIntervalInput"
                   name="floodInterval"
                   min="0"
                   value="<?=$this->escape($this->get('floodInterval')) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <label for="excludeFloodProtection" class="col-lg-2 control-label">
            <?=$this->getTrans('excludeFloodProtection') ?>:
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="excludeFloodProtection"
                    name="groups[]"
                    data-placeholder="<?=$this->getTrans('excludeFloodProtection') ?>"
                    multiple>
                <?php
                /** @var \Modules\User\Models\Group $group */
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
    <div class="row mb-3">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('postVoting') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="postVoting-on" name="postVoting" value="1" <?=($this->get('postVoting') == '1') ? 'checked="checked"' : '' ?> />
                <label for="postVoting-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="postVoting-off" name="postVoting" value="0" <?=($this->get('postVoting') != '1') ? 'checked="checked"' : '' ?> />
                <label for="postVoting-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('topicSubscription') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="topicSubscription-on" name="topicSubscription" value="1" <?=($this->get('topicSubscription') == '1') ? 'checked="checked"' : '' ?> />
                <label for="topicSubscription-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="topicSubscription-off" name="topicSubscription" value="0" <?=($this->get('topicSubscription') != '1') ? 'checked="checked"' : '' ?> />
                <label for="topicSubscription-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('DESCPostorder') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="DESCPostorder-on" name="DESCPostorder" value="1" <?=($this->get('DESCPostorder') == '1') ? 'checked="checked"' : '' ?> />
                <label for="DESCPostorder-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="DESCPostorder-off" name="DESCPostorder" value="0" <?=($this->get('DESCPostorder') != '1') ? 'checked="checked"' : '' ?> />
                <label for="DESCPostorder-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <h2><?=$this->getTrans('boxSettings') ?></h2>
    <div class="row mb-3">
        <label for="boxForumLimit" class="col-lg-2 control-label">
            <?=$this->getTrans('boxForumLimit') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="boxForumLimit"
                   name="boxForumLimit"
                   min="1"
                   value="<?=($this->get('boxForumLimit') != '') ? $this->escape($this->get('boxForumLimit')) : 5 ?>" />
        </div>
    </div>
    <h2><?=$this->getTrans('reportSettings') ?></h2>
    <div class="row mb-3">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('reportingPosts') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="reportingPosts-on" name="reportingPosts" value="1" <?=($this->get('reportingPosts') == '1') ? 'checked="checked"' : '' ?> />
                <label for="reportingPosts-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="reportingPosts-off" name="reportingPosts" value="0" <?=($this->get('reportingPosts') != '1') ? 'checked="checked"' : '' ?> />
                <label for="reportingPosts-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('reportNotificationEMail') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="reportNotificationEMail-on" name="reportNotificationEMail" value="1" <?=($this->get('reportNotificationEMail') == '1') ? 'checked="checked"' : '' ?> />
                <label for="reportNotificationEMail-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="reportNotificationEMail-off" name="reportNotificationEMail" value="0" <?=($this->get('reportNotificationEMail') != '1') ? 'checked="checked"' : '' ?> />
                <label for="reportNotificationEMail-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('#excludeFloodProtection').chosen();
</script>
