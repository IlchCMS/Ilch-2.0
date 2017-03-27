<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Controllers;

use Modules\Teams\Mappers\Teams as TeamsMapper;
use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Models\Joins as JoinsModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $teamsMapper = new TeamsMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTeams'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuTeams'), ['action' => 'index']);

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('groupMapper', $groupMapper);
        $this->getView()->set('teams', $teamsMapper->getTeams());
    }

    public function joinAction()
    {
        $teamsMapper = new TeamsMapper();
        $joinsMapper = new JoinsMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTeams'))
            ->add($this->getTranslator()->trans('menuJoin'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuJoin'), ['action' => 'join']);

        if ($this->getRequest()->isPost()) {
            if ($this->getUser()) {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'name' => 'required|unique:teams_joins,name',
                    'email' => 'required|email|unique:teams_joins,email',
                    'teamId' => 'numeric|integer|min:1',
                    'gender' => 'numeric|integer|min:1|max:2',
                    'age' => 'required',
                    'text' => 'required'
                ]);
            } else {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'name' => 'required|unique:users,name|unique:teams_joins,name',
                    'email' => 'required|email|unique:users,email|unique:teams_joins,email',
                    'teamId' => 'numeric|integer|min:1',
                    'gender' => 'numeric|integer|min:1|max:2',
                    'age' => 'required',
                    'text' => 'required',
                    'captcha' => 'captcha'
                ]);
            }

            if ($validation->isValid()) {
                $model = new JoinsModel();
                $currentDate = new \Ilch\Date();

                if ($this->getUser()) {
                    $model->setUserId($this->getUser()->getId())
                        ->setGender($this->getUser()->getGender());
                } else {
                    $model->setGender($this->getRequest()->getPost('gender'));
                }
                $model->setName($this->getRequest()->getPost('name'))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setPlace($this->getRequest()->getPost('place'))
                    ->setAge(new \Ilch\Date($this->getRequest()->getPost('age')))
                    ->setSkill($this->getRequest()->getPost('skill'))
                    ->setTeamId($this->getRequest()->getPost('teamId'))
                    ->setDateCreated($currentDate->toDb())
                    ->setText($this->getRequest()->getPost('text'));
                $joinsMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag());

            if ($this->getRequest()->getParam('id')) {
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'join', 'id' => $this->getRequest()->getParam('id')]);
            } else {
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'join']);
            }
        }

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('teams', $teamsMapper->getTeams());
    }
}
