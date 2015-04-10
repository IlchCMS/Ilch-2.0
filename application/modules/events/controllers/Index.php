<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Models\Events as EventModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $eventMapper = new EventMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuEventList'), array('controller' => 'index'));

        if ($this->getRequest()->isPost()) {            
            $eventModel = new EventModel();

            $eventModel->setId(trim($this->getRequest()->getPost('id')));
            $eventModel->setUserId($this->getUser()->getId());
            $eventModel->setStatus(trim($this->getRequest()->getPost('save')));
            $eventMapper->saveUserOnEvent($eventModel);

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('eventList', $eventMapper->getEntries());
        $this->getView()->set('eventListUpcoming', $eventMapper->getEventListUpcoming());
        $this->getView()->set('eventListPast', $eventMapper->getEventListPast());
    }

    public function showAction()
    {
        $eventMapper = new EventMapper();
        $eventModel = new EventModel();
            
        $event = $eventMapper->getEventById($this->getRequest()->getParam('id'));
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEventList'), array('action' => 'index'))
                ->add($event->getTitle(), array('action' => 'show', 'id' => $event->getId()));

        if ($this->getRequest()->isPost()) {       
            if ($this->getRequest()->getPost('save')) {         
                $eventModel->setEventId(trim($this->getRequest()->getPost('id')));
                $eventModel->setUserId($this->getUser()->getId());
                $eventModel->setStatus(trim($this->getRequest()->getPost('save')));
                $eventMapper->saveUserOnEvent($eventModel);

                $this->addMessage('saveSuccess');
            }
            if ($this->getRequest()->getPost('commentEvent')) {
                $date = new \Ilch\Date();
                $eventModel->setId(trim($this->getRequest()->getPost('id')));
                $eventModel->setUserId($this->getUser()->getId());
                $eventModel->setDateCreated($date);
                $eventModel->setText(trim($this->getRequest()->getPost('commentEvent')));
                $eventMapper->saveComment($eventModel);

                $this->addMessage('saveSuccess');
            }
            if ($this->getRequest()->getPost('deleteUser')) {
                $eventMapper->deleteUserOnEvent($this->getUser()->getId());

                $this->addMessage('deleteSuccess');
            }
            if ($this->getRequest()->getPost('deleteEvent')) {
                $eventMapper->delete($this->getRequest()->getParam('id'));

                $this->addMessage('deleteSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }

        $this->getView()->set('event', $eventMapper->getEvent($this->getRequest()->getParam('id')));
        $this->getView()->set('eventEntrants', $eventMapper->getEventEntrants($this->getRequest()->getParam('id')));
        $this->getView()->set('eventEntrantsCount', count($eventMapper->getEventEntrants($this->getRequest()->getParam('id'))));
        $this->getView()->set('eventComments', $eventMapper->getEventComments($this->getRequest()->getParam('id')));
    }

    public function treatAction()
    {
        $eventMapper = new EventMapper();
        $eventModel = new EventModel();

        $event = $eventMapper->getEventById($this->getRequest()->getParam('id'));
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEventList'), array('action' => 'index'))
                    ->add($event->getTitle(), array('action' => 'show', 'id' => $event->getId()))
                    ->add($this->getTranslator()->trans('menuActionEditEvent'), array('action' => 'treat', 'id' => $event->getId()));

            $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEventList'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('menuActionNewEvent'), array('action' => 'treat'));            
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('id')) {
                $eventModel->setId($this->getRequest()->getParam('id'));
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
                $eventModel->setUserId($this->getUser()->getId());
                $eventModel->setTitle($title);
                $eventModel->setDateCreated($dateCreated);
                $eventModel->setPlace($place);
                $eventModel->setText($text);
                $eventMapper->save($eventModel);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();
            
            $event = $eventMapper->getCommentEventId($this->getRequest()->getParam('id'));
            $eventId = $event->getEventId();
            $eventMapper->deleteComment($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'show', 'id' => $eventId));
    }
}
