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
        $groupMapper = new GroupMapper();
        $groupAccessList = $groupMapper->getGroupAccessList();
        $groups = $groupMapper->getGroupList();

        $this->getView()->set('groups', $groups);
        $this->getView()->set('groupAccessList', $groupAccessList);

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

        if (isset($postData['groupsAccess'])) {
            $groupsAccessData = $postData['groupsAccess'];
            $groupMapper = new GroupMapper();

            foreach($groupsAccessData as $type => $groupsAccessTypeData) {
                foreach($groupsAccessTypeData as $groupId => $groupAccessTypeData) {
                    foreach($groupAccessTypeData as $typeId => $accessLevel) {
                        $groupMapper->saveAccessData($groupId, $typeId, $accessLevel, $type);
                    }
                }
            }

            $this->redirect(array('action' => 'index'));
        }
    }
}