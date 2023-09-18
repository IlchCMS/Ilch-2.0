<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('limit') ? 'has-error' : '' ?>">
        <label for="limit" class="col-lg-2 control-label">
            <?=$this->getTrans('numberOfMessagesDisplayed') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="limit"
                   name="limit"
                   min="1"
                   value="<?=$this->originalInput('limit', $this->get('limit')) ?>">
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('maxtextlength') ? 'has-error' : '' ?>">
        <label for="maxtextlength" class="col-lg-2 control-label">
            <?=$this->getTrans('maximumTextLength') ?>
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="maxtextlength"
                   name="maxtextlength"
                   min="1"
                   value="<?=$this->originalInput('maxtextlength', $this->get('maxtextlength')) ?>">
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('writeAccess') ? 'has-error' : '' ?>">
        <label for="writeAccess" class="col-lg-2 control-label">
            <?=$this->getTrans('writeAccess') ?>
        </label>
        <div class="col-lg-3">
            <select class="chosen-select form-control"
                    id="writeAccess"
                    name="writeAccess[]"
                    data-placeholder="<?=$this->getTrans('selectAssignedGroups') ?>"
                    multiple>
                <?php
                /** @var \Modules\User\Models\Group $groupList */
                foreach ($this->get('userGroupList') as $groupList) : ?>
                    <option value="<?=$groupList->getId() ?>"
                        <?php $writeAccess = explode(',', $this->originalInput('writeAccess', $this->get('writeAccess')));
                        foreach ($writeAccess as $access) {
                            if ($groupList->getId() == $access) {
                                echo 'selected="selected"';
                                break;
                            }
                        }
                        ?>>
                        <?=$groupList->getName() ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('#writeAccess').chosen();
</script>
