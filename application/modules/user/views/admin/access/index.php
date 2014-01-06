<?php
/**
 * Viewfile for the access page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

if ($this->get('groupAccessList') != '') {
    $groupAccessList = $this->get('groupAccessList');
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
        <?php
        foreach($this->get('accessTypes') as $accessType => $typeData) {
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
                        foreach($this->get('groups') as $group) {
                            echo '<th>'.$this->escape($group->getName()).'</th>';
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
                            foreach($this->get('groups') as $group) {
                                $groupHasEntries = true;

                                if(isset($groupAccessList[$group->getId()]['entries'][$accessType.'s'])) {
                                    $typeEntries = $groupAccessList[$group->getId()]['entries'][$accessType.'s'];
                                } else {
                                    $groupHasEntries = false;
                                }

                                ?>
                                <td>
                                <?php
                                $typeAccessLevel = 1;

                                if($groupHasEntries) {
                                    if(isset($typeEntries[$type->getId()])) {
                                        $typeAccessLevel = (int)$typeEntries[$type->getId()];
                                    }
                                }

                                foreach($accessLevelsTrans as $accessLevel => $text) {
                                    ?>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="groupsAccess<?php echo '['.$accessType.']['.$group->getId().']['.$type->getId().']'; ?>"
                                                   value="<?php echo $accessLevel; ?>"
                                                   <?php echo ($accessLevel == $typeAccessLevel) ? 'checked' : '' ?>/>
                                            <?php echo $this->trans($text); ?>
                                        </label>
                                    </div>
                                    <?php
                                }

                                ?>
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
    </form>
    <?php
} else {
    echo $this->trans('noGroupsExist');
}
?>