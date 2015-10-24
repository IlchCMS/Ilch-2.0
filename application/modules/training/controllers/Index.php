<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Controllers;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Mappers\Entrants as EntrantsMapper;
use Modules\Training\Models\Entrants as EntrantsModel;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $trainingMapper = new TrainingMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuTraining'), array('action' => 'index'));

        $this->getView()->set('training', $trainingMapper->getTraining());
    }

    public function showAction()
    {
        $trainingMapper = new TrainingMapper();
        $entrantsMapper = new EntrantsMapper();
        $entrantsModel = new EntrantsModel();

        $training = $trainingMapper->getTrainingById($this->getRequest()->getParam('id'));
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuTraining'), array('controller' => 'index', 'action' => 'index'))
                ->add($training->getTitle(), array('controller' => 'index', 'action' => 'show', 'id' => $training->getId()));

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

        $this->getView()->set('training', $trainingMapper->getTrainingById($this->getRequest()->getParam('id')));
        $this->getView()->set('trainEntrantsUserCount', count($entrantsMapper->getEntrantsById($this->getRequest()->getParam('id'))));
        $this->getView()->set('trainEntrantsUser', $entrantsMapper->getEntrantsById($this->getRequest()->getParam('id')));
    }
}
