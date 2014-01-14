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
use Page\Mappers\Page as PageMapper;
use Article\Mappers\Article as ArticleMapper;

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
        $postData = $this->getRequest()->getPost();
        $groupMapper = new GroupMapper();
        $groups = $groupMapper->getGroupList();
        $this->getView()->set('activeGroupId', 0);
        $this->getView()->set('activeGroup', null);

        foreach($groups as $key => $group) {
            if($group->getId() == 1) {
                unset($groups[$key]);
            }
        }

        $this->getView()->set('groups', $groups);

        if (isset($postData['groupId'])) {
            $groupId = (int)$postData['groupId'];
            $_SESSION['user']['accessGroup'] = $groupId;
        }
        elseif(isset($_SESSION['user']['accessGroup']))
        {
            $groupId = (int)$_SESSION['user']['accessGroup'];
        }
        else
        {
            $groupId = 0;
        }

        if ($groupId) {
            $groupAccessList = $groupMapper->getGroupAccessList($groupId);
            $activeGroup = $groupMapper->getGroupById($groupId);
            $this->getView()->set('groupAccessList', $groupAccessList);
            $this->getView()->set('activeGroupId', $groupId);
            $this->getView()->set('activeGroup', $activeGroup);
        }

        $moduleMapper = new ModuleMapper();
        $modules = $moduleMapper->getModules();

        $pageMapper = new PageMapper();
        $pages = $pageMapper->getPageList();

        $articleMapper = new ArticleMapper();
        $articles = $articleMapper->getArticles();

        $accessTypes = array(
            'module' => $modules,
            'page' => $pages,
            'article' => $articles,
        );
        $this->getView()->set('accessTypes', $accessTypes);
    }

    /**
     * Saves the group access rights.
     */
    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['groupAccess'], $postData['groupId'])) {
            if((int)$postData['groupId'] !== 1) {
                $groupAccessData = $postData['groupAccess'];
                $groupMapper = new GroupMapper();

                foreach($groupAccessData as $type => $groupsAccessTypeData) {
                    foreach($groupsAccessTypeData as $typeId => $accessLevel) {
                        $groupMapper->saveAccessData($_SESSION['user']['accessGroup'], $typeId, $accessLevel, $type);
                    }
                }
            }

            $this->redirect(array('action' => 'index'));
        }
    }
}