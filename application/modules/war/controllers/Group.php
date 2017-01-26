<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers;

use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Mappers\Games as GamesMapper;

class Group extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroupList'), ['action' => 'index']);

        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('groups', $groupMapper->getGroupList($pagination));
        $this->getView()->set('warMapper', $warMapper);
        $this->getView()->set('gamesMapper', $gamesMapper);
        $this->getView()->set('pagination', $pagination);
    }

    public function showAction()
    {
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $pagination = new \Ilch\Pagination();

        $id = $this->getRequest()->getParam('id');
        $group = $groupMapper->getGroupById($id);
        $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroupList'), ['action' => 'index'])
            ->add($group->getGroupName(), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        $this->getView()->set('group', $group);
        $this->getView()->set('war', $warMapper->getWarsByWhere('group ='.$id, $pagination));
        $this->getView()->set('warMapper', $warMapper);
        $this->getView()->set('gamesMapper', $gamesMapper);
        $this->getView()->set('pagination', $pagination);
    }
}
