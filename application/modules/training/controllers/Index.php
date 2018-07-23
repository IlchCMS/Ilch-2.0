<?php
/**
 * @copyright Ilch 2.0
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
        $userMapper = new UserMapper;

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index']);

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

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('training', $trainingMapper->getTraining())
            ->set('readAccess', $readAccess);
    }

    public function showAction()
    {
        $trainingMapper = new TrainingMapper();
        $entrantsMapper = new EntrantsMapper();
        $entrantsModel = new EntrantsModel();
        $userMapper = new UserMapper;

        $training = $trainingMapper->getTrainingById($this->getRequest()->getParam('id'));
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuTraining'), ['controller' => 'index', 'action' => 'index']);

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $entrantsModel->setTrainId($this->getRequest()->getParam('id'));
                $entrantsModel->setUserId($this->getUser()->getId());
                $entrantsModel->setNote($this->getRequest()->getPost('train_textarea'));
                $entrantsMapper->saveUserOnTrain($entrantsModel);

                $this->addMessage('saveSuccess');
            }
            if ($this->getRequest()->getPost('del')) {
                $entrantsMapper->deleteUserFromTrain($this->getRequest()->getParam('id'), $this->getUser()->getId());

                $this->addMessage('deleteSuccess');
            }
        }

        if ($this->getUser()) {
            $this->getView()->set('trainEntrantUser', $entrantsMapper->getEntrants($this->getRequest()->getParam('id'), $this->getUser()->getId()));
        }

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

        $hasReadAccess = (is_in_array($readAccess, explode(',', $training->getReadAccess())) || $adminAccess == true);

        if ($hasReadAccess) {
            $this->getLayout()->getHmenu()->add($training->getTitle(), ['controller' => 'index', 'action' => 'show', 'id' => $training->getId()]);

            $this->getView()->set('training', $trainingMapper->getTrainingById($this->getRequest()->getParam('id')))
                ->set('trainEntrantsUserCount', count($entrantsMapper->getEntrantsById($this->getRequest()->getParam('id'))))
                ->set('trainEntrantsUser', $entrantsMapper->getEntrantsById($this->getRequest()->getParam('id')));
        }
        $this->getView()->set('hasReadAccess', $hasReadAccess);
    }
}
