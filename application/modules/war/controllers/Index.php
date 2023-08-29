<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Date;
use Ilch\Pagination;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as UserGroupMapper;
use Modules\War\Models\Accept as AcceptModel;
use Modules\War\Mappers\Accept as AcceptMapper;
use Modules\War\Mappers\Maps as MapsMapper;
use Ilch\Comments;
use Ilch\Validation;

class Index extends Frontend
{
    public function indexAction()
    {
        $pagination = new Pagination();
        $warMapper = new WarMapper();
        $userMapper = new UserMapper();
        $gamesMapper = new GamesMapper();
        $groupMapper = new GroupMapper();
        $enemyMapper = new EnemyMapper();

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
            ->set('war', $warMapper->getWarList($pagination, $readAccess))
            ->set('pagination', $pagination)
            ->set('groupMapper', $groupMapper)
            ->set('enemyMapper', $enemyMapper);
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
        $mapsMapper = new MapsMapper();
        
        $date = new Date();
        $datenow = new Date($date->format("Y-m-d H:i:s", true));

        $war = $warMapper->getWarById($this->getRequest()->getParam('id'));

        if ($war) {
            $commentsKey = 'war/index/show/id/'.$war->getId();

            $user = null;
            $adminAccess = null;
            if ($this->getUser()) {
                $user = $userMapper->getUserById($this->getUser()->getId());
                $adminAccess = $this->getUser()->isAdmin();
            }

            $readAccess = [3];
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }

            $hasReadAccess = (is_in_array($readAccess, explode(',', $war->getReadAccess())) || $adminAccess === true);
            if (!$hasReadAccess) {
                $this->redirect()
                    ->withMessage('warNotFound', 'warning')
                    ->to(['action' => 'index']);

                return;
            }
            
            $group = $groupMapper->getGroupById($war->getWarGroup());
            $checkAccept = [];
            $accept = null;
            if ($group) {
                $checkAccept = $acceptMapper->getAcceptListByGroupId($group->getGroupMember(), $war->getId());
                if ($this->getUser()) {
                    foreach ($checkAccept as $check) {
                        if ($this->getUser()->getId() == $check->getUserId()) {
                            $accept = $check;
                            break;
                        }
                    }
                }
            }

            if ($this->getUser()) {
                if ($this->getRequest()->isPost()) {
                    if ($this->getRequest()->getPost('warAccept')) {
                        $validation = Validation::create($this->getRequest()->getPost(), [
                            'warAccept'           => 'required|min:1|max:3'
                        ]);

                        if ($validation->isValid()) {
                            $model = new AcceptModel();
                            if ($accept) {
                                $model->setId($accept->getId());
                            }

                            $model->setWarId($war->getId())
                                ->setUserId($this->getUser()->getId())
                                ->setAccept((int)$this->getRequest()->getPost('warAccept'))
                                ->setComment(trim($this->getRequest()->getPost('warComment')))
                                ->setDateCreated($datenow);
                            $acceptMapper->save($model);

                            $this->redirect()
                                ->withMessage('saveSuccess')
                                ->to(['action' => 'show', 'id' => $war->getId()]);
                            $this->redirect(['action' => 'show', 'id' => $war->getId()]);
                        }
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withInput()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'show', 'id' => $war->getId()]);
                    } elseif ($this->getRequest()->getPost('saveComment')) {
                        $comments = new Comments();

                        if ($this->getRequest()->getPost('fkId')) {
                            $commentsKey .= '/id_c/'.$this->getRequest()->getPost('fkId');
                        }

                        $comments->saveComment($commentsKey, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                        $this->redirect(['action' => 'show', 'id' => $war->getId()]);
                    }
                }

                if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                    $commentId = $this->getRequest()->getParam('commentId');
                    $comments = new Comments();

                    $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                    $this->redirect(['action' => 'show', 'id' => $war->getId().'#comment_'.$commentId]);
                }
            }

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuWarList'), ['action' => 'index'])
                ->add(($group) ? $group->getGroupName() : '', ($group) ? ['controller' => 'group', 'action' => 'show', 'id' => $group->getId()] : '')
                ->add($this->getTranslator()->trans('warPlay'), ['action' => 'show', 'id' => $war->getId()]);


            $this->getView()->set('userMapper', $userMapper)
                ->set('userGroupIds', $userGroupMapper->getUsersForGroup($group ? $group->getGroupMember() : ''))
                ->set('games', $gamesMapper->getGamesByWarId($war->getId()))
                ->set('group', $group)
                ->set('enemy', $enemyMapper->getEnemyById($war->getWarEnemy()))
                ->set('war', $war)
                ->set('accept', $accept)
                ->set('acceptCheck', $checkAccept)
                ->set('commentsKey', 'war/index/show/id/'.$war->getId())
                ->set('datenow', $datenow)
                ->set('mapsMapper', $mapsMapper);
        } else {
            $this->redirect()
                ->withMessage('warNotFound', 'warning')
                ->to(['action' => 'index']);
        }
    }
}
