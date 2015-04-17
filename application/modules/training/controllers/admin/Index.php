<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Controllers\Admin;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Models\Training as TrainingModel;
use Modules\Training\Mappers\Entrants as EntrantsMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuTraining',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
                (
                array
                    (
                    'name' => 'menuAdd',
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTraining'), array('action' => 'index'));

        $trainingMapper = new TrainingMapper();

        if ($this->getRequest()->getPost('check_training')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_training') as $trainingId) {
                    $trainingMapper->delete($trainingId);
                }
            }
        }

        $training = $trainingMapper->getTraining();

        $this->getView()->set('training', $training);
    }

    public function treatAction() 
    {
        $trainingMapper = new TrainingMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuTraining'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('menuEdit'), array('action' => 'treat'));

            $this->getView()->set('training', $trainingMapper->getTrainingById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuTraining'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('menuAdd'), array('action' => 'treat'));            
        }

        if ($this->getRequest()->isPost()) {
            $model = new TrainingModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));

            if(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } else {
                $model->setTitle($title);
                $model->setDate(new \Ilch\Date(trim($this->getRequest()->getPost('date'))));
                $model->setPlace($this->getRequest()->getPost('place'));
                $model->setText($this->getRequest()->getPost('text'));
                $trainingMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $trainingMapper = new TrainingMapper();
            $entrantsMapper = new EntrantsMapper();
            
            $trainingMapper->delete($this->getRequest()->getParam('id'));
            $entrantsMapper->deleteAllUser($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
