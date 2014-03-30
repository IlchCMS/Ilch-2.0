<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */
?>
<form action="<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'access', 'action' => 'save')); ?>"
      method="POST"
      class="form-horizontal"
      role="form"
      id="groupAccessForm">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="groupId"
               class="control-label col-sm-2">
            <?php echo $this->getTrans('group'); ?>
        </label>
        <div class="col-sm-10">
            <select name="groupId"
                    id="groupId"
                    class="form-control">
                <option value="0"
                        <?php echo ((int)$this->get('activeGroupId') == null) ? 'selected="selected"' : '';?>>
                        <?php echo $this->getTrans('chooseAGroup'); ?>
                </option>
                <?php
                foreach($this->get('groups') as $group) {
                    ?>
                    <option value="<?php echo $group->getId();?>"
                            <?php echo ((int)$this->get('activeGroupId') == $group->getId()) ? 'selected="selected"' : '';?>>
                            <?php echo $this->escape($group->getName()); ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    if ($this->get('groupAccessList') != '') {
        $groupAccessList = $this->get('groupAccessList');
        $accessLevelsTrans = array(
            0 => 'noAccess',
            1 => 'lookAccess',
            2 => 'modifyAccess',
        );
        $activeGroup = $this->get('activeGroup');
        ?>
            <?php
            foreach($this->get('accessTypes') as $accessType => $typeData) {
                if(empty($typeData)) {
                    continue;
                }
                ?>
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-lg-9">
                        <col class="col-lg-1">
                        <col class="col-lg-1">
                        <col class="col-lg-1">
                    </colgroup>
                    <thead>
                        <tr>
                            <th><?php echo $this->getTrans($accessType); ?></th>
                            <?php
                            foreach($accessLevelsTrans as $transKey) {
                                echo '<th class="text-center">'.$this->getTrans($transKey).'</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($typeData as $type) {
                            if ($accessType == 'module') {
                                $content = $type->getContentForLocale($this->getTranslator()->getLocale());
                            }
                        ?>
                            <tr>
                                <td style="vertical-align:middle">
                                    <?php
                                    if($accessType == 'module') {
                                        echo $this->escape($content['name']);
                                    } elseif($accessType == 'article') {
                                       echo $this->escape($type->getTitle());
                                    } elseif($accessType == 'page') {
                                        echo $this->escape($type->getTitle());
                                    } elseif($accessType == 'box') {
                                        echo $this->escape($type->getTitle());
                                    }
                                    ?>
                                </td>
                                <?php
                                $groupHasEntries = true;

                                if(isset($groupAccessList['entries'][$accessType])) {
                                    $typeEntries = $groupAccessList['entries'][$accessType];
                                } else {
                                    $groupHasEntries = false;
                                }

                                ?>
                                <?php
                                $typeAccessLevel = 1;

                                if($groupHasEntries) {
                                    if($accessType == 'module') {
                                        if(isset($typeEntries[$type->getKey()])) {
                                            $typeAccessLevel = (int)$typeEntries[$type->getKey()];
                                        }
                                    } else {
                                        if(isset($typeEntries[$type->getId()])) {
                                            $typeAccessLevel = (int)$typeEntries[$type->getId()];
                                        }
                                    }
                                }

                                foreach($accessLevelsTrans as $accessLevel => $transKey) {
                                    ?>
                                    <td class="text-center">
                                        <input type="radio"
                                           <?php
                                             if($accessType == 'module') {
                                                 echo 'name="groupAccess['.$accessType.']['.$type->getKey().']"';
                                              } else {
                                                  echo 'name="groupAccess['.$accessType.']['.$type->getId().']"';
                                              }
                                          ?>
                                           value="<?php echo $accessLevel; ?>"
                                           <?php echo ($accessLevel == $typeAccessLevel) ? 'checked' : '' ?>/>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
        }
        ?>
        <?=$this->getSaveBar()?>
        <?php
    }
    ?>
</form>
<script>
    $('#groupId').on('change', function() {
        if($(this).val() != 0) {
            $('#groupAccessForm').attr('action', '<?php echo $this->getUrl(array('module' => 'user', 'controller' => 'access', 'action' => 'index')); ?>');
            $('#groupAccessForm').submit();
        }
    });
</script>
