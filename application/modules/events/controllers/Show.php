<?php
/**
 * @copyright Ilch 2
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

        if ($event === null) {
            return;
        }

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $date = new \Ilch\Date();

            if ($this->getRequest()->getPost('save')) {
                if (empty($event->getUserLimit()) || ($entrantsMapper->getCountOfEventEntrans($this->getRequest()->getParam('id')) < $event->getUserLimit())) {
                    $entrantsModel->setEventId($this->getRequest()->getParam('id'))
                        ->setUserId($this->getUser()->getId())
                        ->setStatus($this->getRequest()->getPost('save'));
                    $entrantsMapper->saveUserOnEvent($entrantsModel);
                } else {
                    $this->addMessage('maximumEntrantsReached', 'warning');
                }
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

        $eventEntrantsUser = $entrantsMapper->getEventEntrantsById($this->getRequest()->getParam('id'));
        $userDetails = [];
        foreach ($eventEntrantsUser as $entrant) {
            $entrantUserId = $entrant->getUserId();
            $userDetails[$entrantUserId] = $userMapper->getUserById($entrantUserId);
            if (empty($userDetails[$entrantUserId])) {
                $userDetails[$entrantUserId] = $userMapper->getDummyUser();
            }
        }

        $user = null;
        if ($this->getUser()) {
            // Check if user is already in $userDetails
            if (isset($userDetails[$this->getUser()->getId()])) {
                $user = $userDetails[$this->getUser()->getId()];
            } else {
                $user = $userMapper->getUserById($this->getUser()->getId());
            }
        }

        $readAccess = [3];
        $showMembersAccess = [];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
                $showMembersAccess[] = $us->getId();
            }
        } else {
            $showMembersAccess = [3];
        }

        if (is_in_array($readAccess, explode(',', $event->getReadAccess())) || ($this->getUser() && $this->getUser()->isAdmin())) {
            $this->getLayout()->getTitle()
                ->add($event->getTitle());
            $this->getLayout()->getHmenu()
                ->add($event->getTitle(), ['controller' => 'show', 'action' => 'event', 'id' => $event->getId()]);
        } else {
            $event = null;
        }

        $this->getView()->set('userMapper', $userMapper)
            ->set('currencyMapper', $currencyMapper)
            ->set('event', $event)
            ->set('eventEntrantsUser', $eventEntrantsUser)
            ->set('eventEntrantsCount', count($eventEntrantsUser))
            ->set('userDetails', $userDetails)
            ->set('eventComments', $commentMapper->getCommentsByKey('events/show/event/id/'.$this->getRequest()->getParam('id')))
            ->set('event_google_maps_api_key', $this->getConfig()->get('event_google_maps_api_key'))
            ->set('event_google_maps_map_typ', $this->getConfig()->get('event_google_maps_map_typ'))
            ->set('event_google_maps_zoom', $this->getConfig()->get('event_google_maps_zoom'))
            ->set('readAccess', $readAccess)
            ->set('showMembersAccesses', $this->getConfig()->get('event_show_members_accesses'))
            ->set('showMembersAccess', $showMembersAccess);
    }

    public function upcomingAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper;

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsUpcoming'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsUpcoming'), ['action' => 'upcoming']);

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
            ->set('eventListUpcoming', $eventMapper->getEventListUpcoming())
            ->set('readAccess', $readAccess);
    }

    public function CurrentAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper;

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsCurrent'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsCurrent'), ['action' => 'current']);

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
            ->set('eventListCurrent', $eventMapper->getEventListCurrent())
            ->set('readAccess', $readAccess);
    }

    public function pastAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper;

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsPast'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsPast'), ['action' => 'past']);

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
            ->set('eventListPast', $eventMapper->getEventListPast())
            ->set('readAccess', $readAccess);
    }

    public function participationAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'))
            ->add($this->getTranslator()->trans('naviEventsParticipation'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('naviEventsParticipation'), ['action' => 'participation']);

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
            ->set('eventListParticipation', ($this->getUser()) ? $eventMapper->getEventListParticipation($this->getUser()->getId()) : null)
            ->set('readAccess', $readAccess);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $commentMapper = new CommentMapper();

            $commentMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'event', 'id' => $this->getRequest()->getParam('eventid')]);
    }
}
