<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers;

use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\War as WarMapper;

class Group extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), array('controller' => 'index', 'action' => 'index'))
            ->add($this->getTranslator()->trans('menuGroupList'), array('action' => 'index'));

        $groupMapper = new GroupMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('groups', $groupMapper->getGroupList($pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showAction()
    {
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $pagination = new \Ilch\Pagination();

        $id = $this->getRequest()->getParam('id');
        $group = $groupMapper->getGroupById($id);
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), array('controller' => 'index', 'action' => 'index'))
            ->add($this->getTranslator()->trans('menuGroupList'), array('action' => 'index'))
            ->add($group->getGroupName(), array('action' => 'show', 'id' => $this->getRequest()->getParam('id')));

        $this->getView()->set('group', $group);
        $this->getView()->set('war', $warMapper->getWarsByWhere('group ='.$id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }
}
