<?php
/**
 * Holds the class Access.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers\Admin;

use User\Controllers\Admin\Base as BaseController;
use User\Mappers\Group as GroupMapper;
use Admin\Mappers\Module as ModuleMapper;

defined('ACCESS') or die('no direct access');

/**
 * Handles actions for the access assignment page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
class Access extends BaseController
{
    /**
     * Shows a table with all groups.
     */
    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $groupAccessList = $groupMapper->getGroupAccessList();
        $groups = $groupMapper->getGroupList();

        $this->getView()->set('groups', $groups);
        $this->getView()->set('groupAccessList', $groupAccessList);

        $moduleMapper = new ModuleMapper();
        $modules = $moduleMapper->getModules();
        $this->getView()->set('modules', $modules);
    }

    /**
     * Saves the group access rights.
     */
    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['groupsModules'])) {
            $groupsModulesAccessData = $postData['groupsModules'];
            $groupMapper = new GroupMapper();

            foreach($groupsModulesAccessData as $groupId => $groupModulesAccessData) {
                foreach($groupModulesAccessData as $moduleId => $accessLevel) {
                    $groupMapper->saveAccessData($groupId, $moduleId, $accessLevel, 'module');
                }
            }

            $this->redirect(array('action' => 'index'));
        }
    }
}