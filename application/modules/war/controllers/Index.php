<?php
/**
 * @copyright Ilch 2
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
use Ilch\Comments;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $pagination = new \Ilch\Pagination();
        $warMapper = new WarMapper();
        $userMapper = new UserMapper();
        $gamesMapper = new GamesMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), ['action' => 'index']);

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

        $this->getView()->set('gamesMapper', $gamesMapper)
            ->set('war', $warMapper->getWarList($pagination))
            ->set('pagination', $pagination)
            ->set('readAccess', $readAccess);
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
            if ($this->getUser() && $this->getRequest()->isPost()) {
                if ($this->getRequest()->getPost('warAccept')) {
                    $checkAccept = $acceptMapper->getAcceptListByGroupId($group->getGroupMember(), $this->getRequest()->getParam('id'));

                    $model = new AcceptModel();
                    foreach ($checkAccept as $check) {
                        if ($this->getUser()->getId() == $check->getUserId()) {
                            $model->setId($check->getId());
                        }
                    }
                    $model->setWarId($war->getId())
                        ->setUserId($this->getUser()->getId())
                        ->setAccept((int)$this->getRequest()->getPost('warAccept'))
                        ->setComment(trim($this->getRequest()->getPost('warComment')));
                    $acceptMapper->save($model);

                    $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);
                } elseif ($this->getRequest()->getPost('saveComment')) {
                    $comments = new Comments();
                    $key = 'war/index/show/id/'.$this->getRequest()->getParam('id');

                    if ($this->getRequest()->getPost('fkId')) {
                        $key .= '/id_c/'.$this->getRequest()->getPost('fkId');
                    }

                    $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                    $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);
                }
            }

            if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                $commentId = $this->getRequest()->getParam('commentId');
                $comments = new Comments();

                $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id').'#comment_'.$commentId]);
            }

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuWarList'), ['action' => 'index'])
                ->add(($group) ? $group->getGroupName() : '', ($group) ? ['controller' => 'group', 'action' => 'show', 'id' => $group->getId()] : '')
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

            $this->getView()->set('gamesMapper', $gamesMapper)
                ->set('userMapper', $userMapper)
                ->set('userGroupMapper', $userGroupMapper)
                ->set('games', $gamesMapper->getGamesByWarId($this->getRequest()->getParam('id')))
                ->set('group', $group)
                ->set('enemy', $enemyMapper->getEnemyById($war->getWarEnemy()))
                ->set('war', $war)
                ->set('accept', $acceptMapper->getAcceptByWhere(['war_id' => $this->getRequest()->getParam('id')]))
                ->set('acceptCheck', ($group) ? $acceptMapper->getAcceptListByGroupId($group->getGroupMember(), $this->getRequest()->getParam('id')) : '')
                ->set('commentsKey', 'war/index/show/id/'.$this->getRequest()->getParam('id'));
        } else {
            $this->redirect()
                ->withMessage('warNotFound', 'warning')
                ->to(['action' => 'index']);
        }
    }
}
