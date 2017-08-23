<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Mappers\Entrants as EntrantsMapper;
use Modules\Events\Models\Entrants as EntrantsModel;
use Modules\Events\Mappers\Currency as CurrencyMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;

class Show extends \Ilch\Controller\Frontend
{
    public function eventAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();
        $entrantsModel = new EntrantsModel();
        $currencyMapper = new CurrencyMapper();
        $commentMapper = new CommentMapper;
        $commentModel = new CommentModel();
        $userMapper = new UserMapper;

        $event = $eventMapper->getEventById($this->getRequest()->getParam('id'));
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($event->getTitle());
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($event->getTitle(), ['controller' => 'show', 'action' => 'event', 'id' => $event->getId()]);

        if ($this->getRequest()->isPost()) {
            $date = new \Ilch\Date();

            if ($this->getRequest()->getPost('save')) {
                $entrantsModel->setEventId(trim($this->getRequest()->getPost('id')))
                    ->setUserId($this->getUser()->getId())
                    ->setStatus(trim($this->getRequest()->getPost('save')));
                $entrantsMapper->saveUserOnEvent($entrantsModel);
            }
            if ($this->getRequest()->getPost('commentEvent')) {
                $commentModel->setKey('events/show/event/id/'.$this->getRequest()->getParam('id'))
                    ->setText($this->getRequest()->getPost('commentEvent'))
                    ->setDateCreated($date)
                    ->setUserId($this->getUser()->getId());
                $commentMapper->save($commentModel);

                $this->addMessage('saveSuccess');
            }
            if ($this->getRequest()->getPost('deleteUser')) {
                $entrantsMapper->deleteUserFromEvent($this->getRequest()->getParam('id'), $this->getUser()->getId());
            }
            if ($this->getRequest()->getPost('deleteEvent')) {
                $eventMapper->delete($this->getRequest()->getParam('id'));

                $this->addMessage('deleteSuccess');

                $this->redirect(['controller' => 'index', 'action' => 'index']);
            }
        }

        if ($this->getUser()) {
            $this->getView()->set('eventEntrants', $entrantsMapper->getEventEntrants($this->getRequest()->getParam('id'), $this->getUser()->getId()));
        }

        $this->getView()->set('userMapper', $userMapper)
            ->set('currencyMapper', $currencyMapper)
            ->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')))
            ->set('eventEntrantsUser', $entrantsMapper->getEventEntrantsById($this->getRequest()->getParam('id')))
            ->set('eventEntrantsCount', count($entrantsMapper->getEventEntrantsById($this->getRequest()->getParam('id'))))
            ->set('eventComments', $commentMapper->getCommentsByKey('events/show/event/id/'.$this->getRequest()->getParam('id')))
            ->set('event_google_maps_api_key', $this->getConfig()->get('event_google_maps_api_key'))
            ->set('event_google_maps_map_typ', $this->getConfig()->get('event_google_maps_map_typ'))
            ->set('event_google_maps_zoom', $this->getConfig()->get('event_google_maps_zoom'));
    }

    public function upcomingAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsUpcoming'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsUpcoming'), ['action' => 'upcoming']);

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('eventListUpcoming', $eventMapper->getEventListUpcoming());
    }

    public function pastAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsPast'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsPast'), ['action' => 'past']);

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('eventListPast', $eventMapper->getEventListPast());
    }

    public function participationAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsParticipation'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsParticipation'), ['action' => 'participation']);

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('eventListParticipation', $eventMapper->getEventListParticipation($this->getUser()->getId()));
    }

    public function myAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsMy'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsMy'), ['action' => 'my']);

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('eventListMy', $eventMapper->getEntries(['user_id' => $this->getUser()->getId()]));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $commentMapper = new CommentMapper;

            $commentMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'event', 'id' => $this->getRequest()->getParam('eventid')]);
    }
}
