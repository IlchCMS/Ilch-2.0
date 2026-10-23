<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Controllers\Admin;

use Ilch\Pagination;
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
        $pagination = new \Ilch\Pagination();
        $trainingMapper = new TrainingMapper();
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index']);
        if ($this->getRequest()->getPost('check_trainings') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_trainings') as $trainingId) {
                $trainingMapper->delete($trainingId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('trainings', $trainingMapper->getEntriesBy([], ['t.date' => 'ASC'], $pagination));
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

            if (!$model) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('training', $model);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'periodDay' => 'periodEntry',
                'periodDays' => 'periodEntry',
                'periodType' => 'periodEntry',
                'date' => 'start',
            ]);

            $rules = [
                'title' => 'required',
                'date' => 'required|date:Y-m-d\TH\:i',
                'end' => 'required|date:Y-m-d\TH\:i',
                'contact' => 'required|integer|min:1|exists:users,id,id,' . $this->getRequest()->getPost('contact'),
                'voiceServer' => 'required|integer|min:0|max:1',
                'gameServer' => 'required|integer|min:0|max:1',
                'groups' => 'required',
                'calendarShow' => 'required|integer|min:0|max:1',
                'periodType' => 'required',
            ];

            if ($this->getRequest()->getPost('periodType') == 'days') {
                $_POST['periodDay'] = $this->getRequest()->getPost('periodDays');
                $rules['periodDay'] = 'required|numeric|min:1|max:7';
            } elseif ($this->getRequest()->getPost('periodType') != '') {
                $rules['periodDay'] = 'required|numeric|min:1';
            }

            if ($this->getRequest()->getPost('periodType') != '') {
                $rules['repeatUntil'] = 'required|date:Y-m-d\TH\:i';
            }

            // Require at least the address of the voice or gameserver if enabled.
            if ($this->getRequest()->getPost('voiceServer')) {
                $rules['voiceServerIP'] = 'required';
            }
            if ($this->getRequest()->getPost('gameServer')) {
                $rules['gameServerIP'] = 'required';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $rules);
            if ($validation->isValid()) {
                $model->setTitle($this->getRequest()->getPost('title'))
                    ->setDate(new \Ilch\Date($this->getRequest()->getPost('date')))
                    ->setEnd(new \Ilch\Date($this->getRequest()->getPost('end')))
                    ->setPeriodDay($this->getRequest()->getPost('periodDay'))
                    ->setPeriodType($this->getRequest()->getPost('periodType'))
                    ->setRepeatUntil($this->getRequest()->getPost('repeatUntil') ? new \Ilch\Date($this->getRequest()->getPost('repeatUntil')) : '1000-01-01 00:00:00')
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
                ->to(($this->getRequest()->getParam('id')) ? ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')] : ['action' => 'treat']);
        }

        if ($trainingMapper->existsTable('calendar')) {
            $this->getView()->set('calendarShow', 1);
        }

        // Only the currently selected contact person needs to be known by name
        // for the pre-rendered <option>; the rest is loaded on demand by
        // choices.js through usersAction().
        $contactId = $this->getRequest()->getPost('contact');
        if (empty($contactId)) {
            $contactId = $this->getRequest()->getOldInput('contact');
        }
        if (empty($contactId)) {
            $contactId = $model->getContact();
        }

        $this->getView()->set('contactUser', !empty($contactId) ? $userMapper->getUserById((int)$contactId) : null)
            ->set('userGroupList', $groupMapper->getGroupList())
            ->set('groups', explode(',', $model->getReadAccess()));
    }

    public function usersAction()
    {
        header('Content-Type: application/json; charset=utf-8');

        $userMapper = new UserMapper();
        $search = (string)$this->getRequest()->getParam('query', '');

        $where = ['confirmed' => 1];
        if ($search !== '') {
            $where['name LIKE'] = '%' . $search . '%';
        }

        $pagination = new Pagination();
        $pagination->setRowsPerPage(20);

        $this->getView()->set('users', $userMapper->getUserList($where, $pagination, ['id', 'name']));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $trainingMapper = new TrainingMapper();
            $entrantsMapper = new EntrantsMapper();
            $trainingMapper->delete($this->getRequest()->getParam('id'));
            $entrantsMapper->deleteAllUser($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
