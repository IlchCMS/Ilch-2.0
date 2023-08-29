<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Pagination;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\User\Mappers\User as UserMapper;

class Group extends Frontend
{
    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $pagination = new Pagination();

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
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();
        $enemyMapper = new EnemyMapper();
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $pagination = new Pagination();

        $group = $groupMapper->getGroupById($this->getRequest()->getParam('id'));
        if ($group) {
            $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
            $pagination->setPage($this->getRequest()->getParam('page'));
            
            $user = null;
            if ($this->getUser()) {
                $user = $userMapper->getUserById($this->getUser()->getId());
            }

            $readAccess = [3];
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuWarList'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuGroupList'), ['action' => 'index'])
                ->add($group->getGroupName(), ['action' => 'show', 'id' => $group->getId()]);

            $this->getView()->set('wars', $warMapper->getWars(['group' => $group->getId()]))
                ->set('gamesMapper', $gamesMapper)
                ->set('group', $group)
                ->set('war', $warMapper->getWarList($pagination, $readAccess, $group->getId()))
                ->set('pagination', $pagination)
                ->set('groupMapper', $groupMapper)
                ->set('enemyMapper', $enemyMapper);
        } else {
            $this->redirect()
                ->withMessage('groupNotFound', 'warning')
                ->to(['action' => 'index']);
        }
    }
}
