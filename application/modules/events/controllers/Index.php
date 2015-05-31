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
                ->add($this->getTranslator()->trans('menuEvents'), array('controller' => 'index'));

        $upcomingLimit = 5;
        $otherLimit = 5;
        $pastLimit = 5;

        $this->getView()->set('eventList', $eventMapper->getEntries());
        $this->getView()->set('eventListUpcoming', $eventMapper->getEventListUpcoming($upcomingLimit));
        $this->getView()->set('getEventListOther', $eventMapper->getEventListOther($otherLimit));
        $this->getView()->set('eventListPast', $eventMapper->getEventListPast($pastLimit));
    }

    public function treatAction()
    {
        $eventMapper = new EventMapper();
        $eventModel = new EventModel();

        $event = $eventMapper->getEventById($this->getRequest()->getParam('id'));
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'))
                    ->add($event->getTitle(), array('controller' => 'show', 'action' => 'event', 'id' => $event->getId()))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat', 'id' => $event->getId()));

            $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEvents'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));            
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('id')) {
                $eventModel->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));
            $dateCreated = new \Ilch\Date(trim($this->getRequest()->getPost('dateCreated')));
            $place = trim($this->getRequest()->getPost('place'));
            $text = trim($this->getRequest()->getPost('text'));

            if(!empty($_FILES['image']['name'])) {
                $path = 'application/modules/events/static/upload/image/';
                $file = $_FILES['image']['name'];
                $endung = pathinfo($file, PATHINFO_EXTENSION);
                $name = pathinfo($file, PATHINFO_FILENAME);
                $image = $path.$name.'.'.$endung;

                move_uploaded_file($_FILES['image']['tmp_name'], $path.$name.'.'.$endung);
            }

            if (empty($dateCreated)) {
                $this->addMessage('missingDate', 'danger');
            } elseif(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($place)) {
                $this->addMessage('missingPlace', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                if(!empty($_FILES['image']['name'])) {
                    $eventModel->setImage($image);
                }
                $eventModel->setUserId($this->getUser()->getId());
                $eventModel->setTitle($title);
                $eventModel->setDateCreated($dateCreated);
                $eventModel->setPlace($place);
                $eventModel->setText($text);
                $eventModel->setShow(trim($this->getRequest()->getPost('calendarShow')));
                $eventMapper->save($eventModel);

                $this->addMessage('saveSuccess');

                if ($this->getRequest()->getParam('id')) {
                    $eventId = $this->getRequest()->getParam('id');
                    $this->redirect(array('controller' => 'show', 'action' => 'event', 'id' => $eventId));
                } else {
                    $this->redirect(array('controller' => 'show', 'action' => 'my'));
                }
            }
        }

        if ($eventMapper->existsTable('ilch_calendar') == true) {
            $this->getView()->set('calendarShow', 1);
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();

            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

            $this->redirect(array('controller' => 'index', 'action' => 'index'));
    }
}
