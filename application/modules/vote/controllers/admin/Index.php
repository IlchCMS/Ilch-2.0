<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Controllers\Admin;

use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\Vote\Models\Vote as VoteModel;
use Modules\Vote\Mappers\Result as ResultMapper;
use Modules\Vote\Models\Result as ResultModel;
use Modules\User\Mappers\Group as GroupMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuVote',
            $items
        );
    }

    public function indexAction()
    {
        $voteMapper = new VoteMapper();
        $resultMapper = new ResultMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuVote'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_vote') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_vote') as $voteId) {
                $voteMapper->delete($voteId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('votes', $voteMapper->getVotes([], null))
            ->set('resultMapper', $resultMapper);
    }

    public function treatAction()
    {
        $voteMapper = new VoteMapper();
        $resultMapper = new ResultMapper();
        $groupMapper = new GroupMapper();


        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuVote'), ['action' => 'index']);

        $voteModel = new VoteModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
            $voteModel = $voteMapper->getVoteById($this->getRequest()->getParam('id'));

            if (!$voteModel) {
                $this->redirect()
                    ->withMessage('voteNotFound')
                    ->to(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('vote', $voteModel);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'question'      => 'required',
                'reply'         => 'required',
                'groups'        => 'required',
                'access'        => 'required',
                'multiplereply' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    if (in_array('all', $this->getRequest()->getPost('groups'))) {
                        $groups = 'all';
                    } else {
                        $groups = implode(',', $this->getRequest()->getPost('groups'));
                    }
                }

                $access = '';
                if (!empty($this->getRequest()->getPost('access'))) {
                    $access = implode(',', $this->getRequest()->getPost('access'));
                }

                $voteModel->setQuestion($this->getRequest()->getPost('question'))
                    ->setKey('vote/index/index')
                    ->setGroups($groups)
                    ->setReadAccess($access)
                    ->setMultipleReply($this->getRequest()->getPost('multiplereply'));
                $id = $voteMapper->save($voteModel);

                if ($voteModel->getId()) {
                    $resultMapper->delete($voteModel->getId());
                } else {
                    $voteModel->setId($id);
                }

                foreach ($this->getRequest()->getPost('reply') ?? [] as $reply) {
                    $reply = trim($reply);
                    if (!empty($reply)) {
                        $resultModel = new ResultModel();
                        $resultModel->setPollId($voteModel->getId())
                            ->setReply($reply);
                        $resultMapper->saveReply($resultModel);
                    }
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], $voteModel->getId() ? ['id' => $voteModel->getId()] : []));
        }

        $voteRes = null;
        if ($voteModel->getId()) {
            $voteRes = $resultMapper->getVoteRes($voteModel->getId());
        }
        if (!$voteRes) {
            $voteRes = [(new ResultModel())->setPollId($voteModel->getId())];
        }
        $this->getView()->set('userGroupList', $groupMapper->getGroupList())
            ->set('voteRes', $voteRes);
    }

    public function lockAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('id')) {
            $voteMapper = new VoteMapper();
            $voteMapper->lock($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function resetAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('id')) {
            $resultMapper = new ResultMapper();
            $resultMapper->resetResult($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('id')) {
            $voteMapper = new VoteMapper();
            $voteMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
