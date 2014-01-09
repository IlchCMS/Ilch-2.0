<?php
/**
 * Viewfile for the access page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
?>
<form action="<?php echo $this->url(array('module' => 'user', 'controller' => 'access', 'action' => 'save')); ?>"
      method="POST"
      class="form-horizontal"
      role="form"
      id="groupAccessForm">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="groupId"
               class="control-label col-sm-2">
            <?php echo $this->trans('group'); ?>
        </label>
        <div class="col-sm-10">
            <select name="groupId"
                    id="groupId"
                    class="form-control">
                <option value="0"
                        <?php echo ((int)$this->get('activeGroupId') == null) ? 'selected="selected"' : '';?>>
                        <?php echo $this->trans('chooseAGroup'); ?>
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
                        <col class="col-lg-2">
                        <col />
                    </colgroup>
                    <thead>
                        <tr>
                            <th><?php echo $this->trans($accessType); ?></th>
                            <?php
                            foreach($accessLevelsTrans as $transKey) {
                                echo '<th>'.$this->trans($transKey).'</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($typeData as $type) {
                        ?>
                            <tr>
                                <td style="vertical-align:middle">
                                    <?php
                                    if($accessType == 'module') {
                                        echo $this->escape($type->getName($this->getTranslator()->getLocale()));
                                    } elseif($accessType == 'article') {
                                       echo $this->escape($type->getTitle());
                                    } elseif($accessType == 'page') {
                                        echo $this->escape($type->getTitle());
                                    }
                                    ?>
                                </td>
                                <?php
                                $groupHasEntries = true;

                                if(isset($groupAccessList['entries'][$accessType.'s'])) {
                                    $typeEntries = $groupAccessList['entries'][$accessType.'s'];
                                } else {
                                    $groupHasEntries = false;
                                }

                                ?>
                                <?php
                                $typeAccessLevel = 1;

                                if($groupHasEntries) {
                                    if(isset($typeEntries[$type->getId()])) {
                                        $typeAccessLevel = (int)$typeEntries[$type->getId()];
                                    }
                                }

                                foreach($accessLevelsTrans as $accessLevel => $transKey) {
                                    ?>
                                    <td>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"
                                                       name="groupAccess<?php echo '['.$accessType.']['.$type->getId().']'; ?>"
                                                       value="<?php echo $accessLevel; ?>"
                                                       <?php echo ($accessLevel == $typeAccessLevel) ? 'checked' : '' ?>/>
                                                <?php echo $this->trans($transKey); ?>
                                            </label>
                                        </div>
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
        <div class="content_savebox">
            <input id="formSubmit"
                   type="submit"
                   class="btn btn-default"
                   value="<?php echo $this->trans('save'); ?>" />
        </div>
        <?php
    }
    ?>
</form>
<script>
    $('#groupId').on('change', function() {
        if($(this).val() != 0) {
            $('#groupAccessForm').attr('action', '<?php echo $this->url(array('module' => 'user', 'controller' => 'access', 'action' => 'index')); ?>');
            $('#groupAccessForm').submit();
        }
    });
</script>
