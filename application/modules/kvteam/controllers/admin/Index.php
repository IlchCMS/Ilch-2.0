<?php

/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvteam\Controllers\Admin;

use Modules\Kvteam\Mappers\Team as TeamMapper;
use Modules\Kvteam\Models\Team as TeamModel;
use Modules\User\Mappers\User as UserMapper;
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

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuTeam',
            $items
        );
    }

    public function indexAction()
    {
        $teamMapper = new TeamMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeam'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_teams')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_teams') as $teamId) {
                    $teamMapper->delete($teamId);
                }
            }
        }

        if ($this->getRequest()->getPost('saveTeam')) {
            foreach ($this->getRequest()->getPost('items') as $i => $teamId) {
                $teamMapper->sort($teamId, $i);
            }

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('teams', $teamMapper->getTeams());
    }

    public function treatAction()
    {
        $teamMapper = new TeamMapper();
        $userMapper = new UserMapper();

        $team = new TeamModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTeam'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $team = $teamMapper->getTeamById($this->getRequest()->getParam('id'));

            if (!$team) {
                $this->redirect()
                    ->withMessage('notfound', 'danger')
                    ->to(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTeam'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('team', $team);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required|unique:kvteam,title,' . $this->getRequest()->getParam('id'),
                'userIds' => 'required'
            ]);

            if ($validation->isValid()) {
                $userIds = implode(",", $this->getRequest()->getPost('userIds'));

                $team->setTitle($this->getRequest()->getPost('title'))
                    ->setUserIds($userIds);
                $teamMapper->save($team);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        $this->getView()->set('userList', $userMapper->getUserList());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $teamMapper = new TeamMapper();
            $teamMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
