<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Controllers;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Mappers\Entrants as EntrantsMapper;
use Modules\Training\Models\Entrants as EntrantsModel;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $trainingMapper = new TrainingMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper();
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index']);
        $groupIds = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('training', $trainingMapper->getTraining([], $groupIds));
    }

    public function showAction()
    {
        $trainingMapper = new TrainingMapper();
        $entrantsMapper = new EntrantsMapper();
        $entrantsModel = new EntrantsModel();
        $userMapper = new UserMapper();

        $groupIds = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $training = $trainingMapper->getTrainingById($this->getRequest()->getParam('id', 0), $groupIds);
        if (!$training) {
            $this->redirect(['action' => 'index'])
                ->withMessage('noTraining', 'danger');
        }
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuTraining'), ['controller' => 'index', 'action' => 'index'])
            ->add($training->getTitle(), ['controller' => 'index', 'action' => 'show', 'id' => $training->getId()]);

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save') && $this->getUser()) {
                $entrantsModel->setTrainId($training->getId())
                    ->setUserId($this->getUser()->getId())
                    ->setNote($this->getRequest()->getPost('train_textarea'));
                $entrantsMapper->saveUserOnTrain($entrantsModel);
                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } elseif ($this->getRequest()->getPost('del') && $this->getUser()) {
                $entrantsMapper->deleteUserFromTrain($training->getId(), $this->getUser()->getId());
                $this->redirect()
                    ->withMessage('deleteSuccess')
                    ->to(['action' => 'index']);
            }
            $this->redirect()
                ->to(['action' => 'index']);
        }

        if ($this->getUser()) {
            $this->getView()->set('trainEntrantUser', $entrantsMapper->getEntrants($training->getId(), $this->getUser()->getId()));
        }

        $trainEntrantsUser = $entrantsMapper->getEntrantsById($training->getId());
        $this->getView()->set('training', $training)
            ->set('trainEntrantsUser', $trainEntrantsUser);
    }
}
