<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Controllers\Admin;

use Ilch\Validation;
use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Models\Training as TrainingModel;
use Modules\Training\Mappers\Entrants as EntrantsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;

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
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];
        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu('menuTraining', $items);
    }

    public function indexAction()
    {
        $trainingMapper = new TrainingMapper();
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index']);
        if ($this->getRequest()->getPost('check_training') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_training') as $trainingId) {
                $trainingMapper->delete($trainingId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('training', $trainingMapper->getEntriesBy());
    }

    public function treatAction()
    {
        $trainingMapper = new TrainingMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();

        $model = new TrainingModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $model =  $trainingMapper->getTrainingById($this->getRequest()->getParam('id'), null);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('training', $model);

        if ($this->getRequest()->isPost()) {
            $rules = [
                'title' => 'required',
                'contact' => 'required',
                'groups' => 'required',
                'time' => 'required|integer'
            ];

            $validation = Validation::create($this->getRequest()->getPost(), $rules);
            if ($validation->isValid()) {
                $model->setTitle($this->getRequest()->getPost('title'))
                    ->setDate(new \Ilch\Date($this->getRequest()->getPost('date')))
                    ->setTime($this->getRequest()->getPost('time'))
                    ->setPlace($this->getRequest()->getPost('place'))
                    ->setContact($this->getRequest()->getPost('contact'))
                    ->setVoiceServer($this->getRequest()->getPost('voiceServer') ?? false)
                    ->setVoiceServerIP($this->getRequest()->getPost('voiceServerIP'))
                    ->setVoiceServerPW($this->getRequest()->getPost('voiceServerPW'))
                    ->setGameServer($this->getRequest()->getPost('gameServer') ?? false)
                    ->setGameServerIP($this->getRequest()->getPost('gameServerIP'))
                    ->setGameServerPW($this->getRequest()->getPost('gameServerPW'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setShow($this->getRequest()->getPost('calendarShow'))
                    ->setReadAccess(implode(',', $this->getRequest()->getPost('groups')));

                $trainingMapper->save($model);

                $this->redirect(['action' => 'index'])
                    ->withMessage('saveSuccess');
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        if ($trainingMapper->existsTable('calendar')) {
            $this->getView()->set('calendarShow', 1);
        }

        $this->getView()->set('users', $userMapper->getUserList(['confirmed' => 1]))
            ->set('userGroupList', $groupMapper->getGroupList())
            ->set('groups', explode(',', $model->getReadAccess()));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $trainingMapper = new TrainingMapper();
            $entrantsMapper = new EntrantsMapper();
            $trainingMapper->delete($this->getRequest()->getParam('id', 0));
            $entrantsMapper->deleteAllUser($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
