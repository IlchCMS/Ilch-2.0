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
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuVote',
            $items
        );
    }

    public function indexAction()
    {
        $voteMapper = new VoteMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuVote'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_vote') && $this->getRequest()->getPost('action') === 'delete') {
            foreach($this->getRequest()->getPost('check_vote') as $voteId) {
                $voteMapper->delete($voteId);
            }
        }

        $this->getView()->set('vote', $voteMapper->getVotes());
    }

    public function treatAction()
    {
        $voteMapper = new VoteMapper();
        $resultMapper = new ResultMapper();
        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuVote'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('vote', $voteMapper->getVoteById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuVote'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'question' => 'required',
                'reply'    => 'required'
            ]);

            if ($validation->isValid()) {
                $voteModel = new VoteModel();
                if ($this->getRequest()->getParam('id')) {
                    $voteModel->setId($this->getRequest()->getParam('id'));
                }

                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    $groups = implode(',', $this->getRequest()->getPost('groups'));
                }

                $participationGroups = '';
                if (!empty($this->getRequest()->getPost('participationGroups'))) {
                    $participationGroups = implode(',', $this->getRequest()->getPost('participationGroups'));
                }

                $voteModel->setQuestion($this->getRequest()->getPost('question'))
                    ->setKey('vote/index/index')
                    ->setGroups($participationGroups)
                    ->setReadAccess($groups);
                $voteMapper->save($voteModel);

                if ($this->getRequest()->getParam('id')) {
                    $resultMapper->delete($this->getRequest()->getParam('id'));

                    foreach($this->getRequest()->getPost('reply') as $key => $reply) {
                        $resultModel = new ResultModel();
                        $resultModel->setPollId($this->getRequest()->getParam('id'))
                            ->setReply($reply);
                        $resultMapper->saveReply($resultModel);
                    }
                } else {
                    foreach($this->getRequest()->getPost('reply') as $key => $reply) {
                        $resultModel = new ResultModel();
                        $resultModel->setPollId($voteMapper->getLastId())
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
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }

        if ($this->getRequest()->getParam('id')) {
            $groups = explode(',', $voteMapper->getVoteById($this->getRequest()->getParam('id'))->getReadAccess());
            $participationGroups = explode(',', $voteMapper->getVoteById($this->getRequest()->getParam('id'))->getGroups());
        } else {
            $groups = [2,3];
            $participationGroups = [];
        }

        $this->getView()->set('userGroupList', $groupMapper->getGroupList())
            ->set('groups', $groups)
            ->set('participationGroups', $participationGroups);
    }

    public function lockAction()
    {
        if ($this->getRequest()->isSecure()) {
            $voteMapper = new VoteMapper();
            $voteMapper->lock($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }

    public function resetAction()
    {
        if ($this->getRequest()->isSecure()) {
            $resultMapper = new ResultMapper();
            $resultMapper->resetResult($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $voteMapper = new VoteMapper();
            $voteMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect(['action' => 'index']);
    }
}
