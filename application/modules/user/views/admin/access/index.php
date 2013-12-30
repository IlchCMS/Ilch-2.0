<?php
/**
 * Viewfile for the access page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

if ($this->get('groupAccessList') != '') {
    $groupAccessList = $this->get('groupAccessList');
    $modules = $this->get('modules');
    $accessLevelsTrans = array(
        0 => 'noAccess',
        1 => 'lookAccess',
        2 => 'modifyAccess'
    );
    ?>
    <form action="<?php echo $this->url(array('module' => 'user', 'controller' => 'access', 'action' => 'save')); ?>"
          method="POST"
          class="form-horizontal"
          id="groupAccessForm">
        <?php echo $this->getTokenField(); ?>
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-lg-2">
                <col />
            </colgroup>
            <thead>
                <tr>
                    <th><?php echo $this->trans('groupName'); ?></th>
                    <?php
                    foreach($modules as $module) {
                        echo '<th>'.$this->escape($module->getName($this->getTranslator()->getLocale())).'</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($this->get('groups') as $group)
                {
                ?>
                    <tr>
                        <td>
                            <?php echo $this->escape($group->getName()); ?>
                        </td>
                        <?php
                        $groupHasEntries = true;

                        if(isset($groupAccessList[$group->getId()]['entries']['modules'])) {
                            $moduleEntries = $groupAccessList[$group->getId()]['entries']['modules'];
                        } else {
                            $groupHasEntries = false;
                        }

                        foreach($modules as $module) {
                            echo '<td>';
                            $moduleAccessLevel = 1;

                            if($groupHasEntries) {
                                if(isset($moduleEntries[$module->getId()])) {
                                    $moduleAccessLevel = (int)$moduleEntries[$module->getId()];
                                }
                            }
                            ?>
                            <select name="groupsModules<?php echo '['.$group->getId().']['.$module->getId().']'; ?>"
                                    id="<?php echo $group->getId().'_'.$module->getId(); ?>"
                                    class="form-control">
                                <?php
                                foreach($accessLevelsTrans as $accessLevel => $text) {
                                    ?>
                                    <option value="<?php echo $accessLevel; ?>"
                                            <?php echo ($accessLevel == $moduleAccessLevel) ? 'selected="selected"' : '' ?>><?php echo $this->trans($text); ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php
                            echo '</td>';
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="content_savebox">
            <input id="formSubmit"
                   type="submit"
                   class="btn btn-default"
                   value="<?php echo $this->trans('save'); ?>" />
        </div>
    </form>
    <?php
} else {
    echo $this->trans('noGroupsExist');
}
?>