<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Models\Events as EventModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
                (
                'menuEvents', array
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
                    'name' => 'menuActionNewEvent',
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'));

        $eventMapper = new EventMapper();

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $eventId) {
                    $eventMapper->delete($eventId);
                }
            }
        }

        $event = $eventMapper->getEntries();

        $this->getView()->set('event', $event);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function showAction()
    {
        if ($this->getRequest()->isPost('delete')) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(array('action' => 'index'));
        }
        $eventMapper = new EventMapper();
        $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuEventTreat'), array('action' => 'treat'));

        $eventMapper = new EventMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
        }

        if ($this->getRequest()->isPost()) {
            $model = new EventModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $title = trim($this->getRequest()->getPost('title'));
            $dateCreated = new \Ilch\Date(trim($this->getRequest()->getPost('dateCreated')));
            $place = trim($this->getRequest()->getPost('place'));
            $text = trim($this->getRequest()->getPost('text'));
            
            if (empty($dateCreated)) {
                $this->addMessage('missingDate', 'danger');
            } elseif(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($place)) {
                $this->addMessage('missingPlace', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $model->setUserId($this->getUser()->getId());
                $model->setTitle($title);
                $model->setDateCreated($dateCreated);
                $model->setPlace($place);
                $model->setText($text);
                $eventMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}
