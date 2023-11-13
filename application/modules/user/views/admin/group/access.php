<h1><?=$this->getTrans('menuAccess') ?></h1>
<form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'saveAccess']) ?>"
      method="POST"
      class="form-horizontal"
      role="form"
      id="groupAccessForm">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="groupId" class="control-label col-md-2"><?=$this->getTrans('group') ?></label>
        <div class="col-md-10">
            <select class="form-control form-select" id="groupId" name="groupId">
                <option value="0"
                        <?=(empty((int)$this->get('activeGroupId'))) ? 'selected="selected"' : '' ?>>
                        <?=$this->getTrans('chooseAGroup') ?>
                </option>
                <?php foreach ($this->get('groups') as $group): ?>
                    <option value="<?=$group->getId() ?>"
                        <?=((int)$this->get('activeGroupId') == $group->getId()) ? 'selected="selected"' : '' ?>>
                        <?=$this->escape($group->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="accessId" class="control-label col-md-2">
        <?php
        $accessTypes = $this->get('accessTypes');
        foreach ($accessTypes as $accessType => $typeData) {
            reset($accessTypes);
            if ($accessType !== key($accessTypes)) {
                echo ' / ';
            }
            echo $this->getTrans($accessType);
        }
        $activeaccess = '';
        ?></label>
        <div class="col-md-10">
            <select class="form-control form-select" id="accessId" name="accessId">
                <option value="0"
                        <?=(empty((int)$this->get('activeaccessId'))) ? 'selected="selected"' : '' ?>>
                        <?=$this->getTrans('choose') ?>
                </option>
                <?php foreach ($accessTypes as $accessType => $typeData): ?>
                    <optgroup label="<?=$this->getTrans($accessType) ?>">
                    <?php foreach ($typeData as $type): ?>
                        <option value="<?=$accessType.'_'.($accessType === 'module'?$type->getKey():$type->getId()) ?>"
                            <?=(!empty($this->get('activeaccessId')) and $this->get('activeaccessId') == $accessType.'_'.($accessType === 'module'?$type->getKey():$type->getId())) ? 'selected="selected"' : '' ?>>
                            <?php
                            if ($accessType === 'module') {
                                $content = $type->getContentForLocale($this->getTranslator()->getLocale());
                                echo $this->escape($content['name']);
                                if ($this->get('activeaccessId') == $accessType.'_'.($type->getKey())) {
                                    $activeaccess = $this->escape($content['name']);
                                }
                            } elseif ($accessType === 'article') {
                                echo $this->escape($type->getTitle());
                                if ($this->get('activeaccessId') == $accessType.'_'.($type->getId())) {
                                    $activeaccess = $this->escape($type->getTitle());
                                }
                            } elseif ($accessType === 'page') {
                                echo $this->escape($type->getTitle());
                                if ($this->get('activeaccessId') == $accessType.'_'.($type->getId())) {
                                    $activeaccess = $this->escape($type->getTitle());
                                }
                            } elseif ($accessType === 'box') {
                                echo $this->escape($type->getTitle());
                                if ($this->get('activeaccessId') == $accessType.'_'.($type->getId())) {
                                    $activeaccess = $this->escape($type->getTitle());
                                }
                            }
                            ?>
                        </option>
                    <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php
    $accessLevelsTrans = [
        0 => 'noAccess',
        1 => 'lookAccess',
        2 => 'modifyAccess',
    ];
    if ($this->get('groupAccessList') != ''):
        $groupAccessList = $this->get('groupAccessList');
        $activeGroup = $this->get('activeGroup');

        foreach ($this->get('accessTypes') as $accessType => $typeData):
            if (empty($typeData)) {
                continue;
            }
            ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col>
                        <col class="col-xl-2">
                        <col class="col-xl-2">
                        <col class="col-xl-2">
                    </colgroup>
                    <thead>
                        <tr>
                            <th><?=$this->getTrans($accessType) ?></th>
                            <?php
                            foreach ($accessLevelsTrans as $transKey) {
                                echo '<th class="text-center">'.$this->getTrans($transKey).'</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($typeData as $type):
                            if ($accessType === 'module') {
                                $content = $type->getContentForLocale($this->getTranslator()->getLocale());
                            }
                        ?>
                            <tr>
                                <td style="vertical-align:middle">
                                    <?php
                                    if ($accessType === 'module') {
                                        echo $this->escape($content['name']);
                                    } elseif ($accessType === 'article') {
                                        echo $this->escape($type->getTitle());
                                    } elseif ($accessType === 'page') {
                                        echo $this->escape($type->getTitle());
                                    } elseif ($accessType === 'box') {
                                        echo $this->escape($type->getTitle());
                                    }
                                    ?>
                                </td>
                                <?php
                                $groupHasEntries = true;

                                if (isset($groupAccessList['entries'][$accessType])) {
                                    $typeEntries = $groupAccessList['entries'][$accessType];
                                } else {
                                    $groupHasEntries = false;
                                }

                                $typeAccessLevel = 1;

                                if ($groupHasEntries) {
                                    if ($accessType === 'module') {
                                        if (isset($typeEntries[$type->getKey()])) {
                                            $typeAccessLevel = (int)$typeEntries[$type->getKey()];
                                        }
                                    } elseif (isset($typeEntries[$type->getId()])) {
                                        $typeAccessLevel = (int)$typeEntries[$type->getId()];
                                    }
                                }

                                foreach ($accessLevelsTrans as $accessLevel => $transKey): ?>
                                    <td class="text-center">
                                        <input type="radio"
                                           <?php
                                            if ($accessType === 'module') {
                                                echo 'name="groupAccess['.$accessType.']['.$type->getKey().']"';
                                            } else {
                                                echo 'name="groupAccess['.$accessType.']['.$type->getId().']"';
                                            }
                                            ?>
                                           value="<?=$accessLevel ?>"
                                           <?=($accessLevel == $typeAccessLevel) ? 'checked' : '' ?> />
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
        <?=$this->getSaveBar() ?>
    <?php elseif ($this->get('accessAccessList') != ''):
        $accessAccessList = $this->get('accessAccessList');
            ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col>
                        <col class="col-xl-2">
                        <col class="col-xl-2">
                        <col class="col-xl-2">
                    </colgroup>
                    <thead>
                        <tr>
                            <th><?=$activeaccess ?></th>
                            <?php
                            foreach ($accessLevelsTrans as $transKey) {
                                echo '<th class="text-center">'.$this->getTrans($transKey).'</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($this->get('groups') as $group):
                        ?>
                            <tr>
                                <td style="vertical-align:middle"><?=$this->escape($group->getName()) ?></td>
                                <?php
                                $typeAccessLevel = $accessAccessList['entries'][$group->getId()] ?? 1;

                                foreach ($accessLevelsTrans as $accessLevel => $transKey): ?>
                                    <td class="text-center">
                                        <input type="radio"
                                           <?php
                                           echo 'name="accessAccess['.$group->getId().']"';
                                           ?>
                                           value="<?=$accessLevel ?>"
                                           <?=($accessLevel == $typeAccessLevel) ? 'checked' : '' ?> />
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?=$this->getSaveBar() ?>
    <?php endif; ?>
</form>
<script>
$('#groupId').on('change', function() {
    if ($(this).val() != 0) {
        $('#accessId').val(0);
        $('#groupAccessForm').attr('action', '<?=$this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'access']) ?>');
        $('#groupAccessForm').submit();
    }
});
$('#accessId').on('change', function() {
    if ($(this).val() != 0) {
        $('#groupId').val(0);
        $('#groupAccessForm').attr('action', '<?=$this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'access']) ?>');
        $('#groupAccessForm').submit();
    }
});
</script>
