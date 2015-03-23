<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers;

use Modules\War\Mappers\Group as GroupMapper;

defined('ACCESS') or die('no direct access');

class Group extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('groups', $groupMapper->getGroups(array(), $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showAction()
    {
        $groupMapper = new GroupMapper();

        $id = $this->getRequest()->getParam('id');

        $this->getView()->set('group', $groupMapper->getGroupById($id));
    }
}
