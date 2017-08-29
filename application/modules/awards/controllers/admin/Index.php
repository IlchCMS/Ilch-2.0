<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Controllers\Admin;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;
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

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuAwards',
            $items
        );
    }

    public function indexAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        if ($awardsMapper->existsTable('teams') == true) {
            $teamsMapper = new TeamsMapper();
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $awardsId) {
                    $awardsMapper->delete($awardsId);
                }
            }
        }

        if ($awardsMapper->existsTable('teams') == true) {
            $this->getView()->set('teamsMapper', $teamsMapper);
        }

        $this->getView()->set('userMapper', $userMapper)
            ->set('awards', $awardsMapper->getAwards());
    }

    public function treatAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        if ($awardsMapper->existsTable('teams') == true) {
            $teamsMapper = new TeamsMapper();
        }

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('awards', $awardsMapper->getAwardsById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'utId' => 'invalidUserTeam',
                'typ' => 'invalidUserTeam',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'date'  => 'required',
                'rank'  => 'required|numeric|integer|min:1',
                'utId'  => 'required|numeric|integer|min:1',
                'typ'  => 'required|numeric|integer|min:1|max:2',
                'event' => 'required',
                'page' => 'url',
            ]);

            if ($validation->isValid()) {
                $model = new AwardsModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setDate(new \Ilch\Date($this->getRequest()->getPost('date')))
                    ->setRank($this->getRequest()->getPost('rank'))
                    ->setTyp($this->getRequest()->getPost('typ'))
                    ->setUTId($this->getRequest()->getPost('utId'))
                    ->setEvent($this->getRequest()->getPost('event'))
                    ->setURL($this->getRequest()->getPost('page'));
                $awardsMapper->save($model);

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

        if ($awardsMapper->existsTable('teams') == true) {
            $this->getView()->set('teams', $teamsMapper->getTeams());
        }

        $this->getView()->set('awardsMapper', $awardsMapper)
            ->set('users', $userMapper->getUserList(['confirmed' => 1]));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awardsMapper = new AwardsMapper();
            $awardsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
