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
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroupList'), ['action' => 'index']);

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('warMapper', $warMapper)
            ->set('gamesMapper', $gamesMapper)
            ->set('groups', $groupMapper->getGroupList($pagination))
            ->set('pagination', $pagination);
    }

    public function showAction()
    {
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $pagination = new \Ilch\Pagination();

        $id = $this->getRequest()->getParam('id');
        $group = $groupMapper->getGroupById($id);
        if ($group) {
            $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
            $pagination->setPage($this->getRequest()->getParam('page'));

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuWarList'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuGroupList'), ['action' => 'index'])
                ->add($group->getGroupName(), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

            $this->getView()->set('warMapper', $warMapper)
                ->set('gamesMapper', $gamesMapper)
                ->set('group', $group)
                ->set('war', $warMapper->getWarsByWhere(['w.group' => (int)$id], $pagination))
                ->set('pagination', $pagination);
        } else {
            $this->redirect()
                ->withMessage('groupNotFound', 'warning')
                ->to(['action' => 'index']);
        }
    }
}
