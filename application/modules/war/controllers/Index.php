<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers;

use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as UserGroupMapper;
use Modules\War\Models\Accept as AcceptModel;
use Modules\War\Mappers\Accept as AcceptMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuWarList'), ['action' => 'index']);

        $pagination = new \Ilch\Pagination();
        $warMapper = new WarMapper();
        $userMapper = new UserMapper();

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

        $this->getView()->set('war', $warMapper->getWarList($pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('readAccess', $readAccess);
    }

    public function showAction()
    {
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $groupMapper = new GroupMapper();
        $enemyMapper = new EnemyMapper();
        $userMapper = new UserMapper();
        $userGroupMapper = new UserGroupMapper();
        $acceptMapper = new AcceptMapper();

        $war = $warMapper->getWarById($this->getRequest()->getParam('id'));

        if ($war) {
            $group = $groupMapper->getGroupById($war->getWarGroup());
            $enemy = $enemyMapper->getEnemyById($war->getWarEnemy());

            if ($this->getRequest()->isPost() && $this->getUser()) {
                $checkAccept = $acceptMapper->getAcceptListByGroupId($group->getGroupMember(), $this->getRequest()->getParam('id'));
                $warAccept = (int)$this->getRequest()->getPost('warAccept');
                $model = new AcceptModel();

                foreach ($checkAccept as $check) {
                    if($this->getUser()->getId() == $check->getUserId()) {
                        $model->setId($check->getId());
                    }
                }
                $model->setWarId($war->getId());
                $model->setUserId($this->getUser()->getId());
                $model->setAccept($warAccept);

                $acceptMapper->save($model);

                $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);
            }

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuWarList'), ['action' => 'index'])
                ->add($group != '' ? $group->getGroupName(): '', $group != '' ? ['controller' => 'group', 'action' => 'show', 'id' => $group->getId()] : '')
                ->add($this->getTranslator()->trans('warPlay'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

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

            $adminAccess = null;
            if ($this->getUser()) {
                $adminAccess = $this->getUser()->isAdmin();
            }

            $hasReadAccess = (is_in_array($readAccess, explode(',', $war->getReadAccess())) || $adminAccess == true);

            if (!$hasReadAccess) {
                $this->redirect()
                    ->withMessage('warNotFound', 'warning')
                    ->to(['action' => 'index']);
                return;
            }

            $this->getView()->set('games', $gamesMapper->getGamesByWarId($this->getRequest()->getParam('id')));
            $this->getView()->set('gamesMapper', $gamesMapper);
            $this->getView()->set('group', $group);
            $this->getView()->set('enemy', $enemy);
            $this->getView()->set('war', $war);
            $this->getView()->set('userMapper', $userMapper);
            $this->getView()->set('userGroupMapper', $userGroupMapper);
            $this->getView()->set('accept', $acceptMapper->getAcceptByWhere(['war_id' => $this->getRequest()->getParam('id')]));
            $this->getView()->set('acceptCheck', $group != '' ? $acceptMapper->getAcceptListByGroupId($group->getGroupMember(), $this->getRequest()->getParam('id')) : '');
        } else {
            $this->redirect()
                ->withMessage('warNotFound', 'warning')
                ->to(['action' => 'index']);
        }
    }
}
