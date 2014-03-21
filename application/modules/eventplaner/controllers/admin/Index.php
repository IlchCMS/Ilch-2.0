<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Controllers\Admin;

defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as EventMapper;
use Eventplaner\Models\Eventplaner as EventModel;
use User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
{

    public function init()
    {
        $this->getLayout()->addMenu
        (
            'eventplaner',
            array
            (
                array
                (
                    'name' => 'listView',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'newEvent',
                    'active' => true,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
            )
        );
        
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'settings',
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
            )
        );
    }
	
    public function indexAction()
    {
        $date = new \Ilch\Date();
        $eventMapper = new EventMapper();

        if( $status = $this->getRequest()->getParam('status') ){
            $status = array('status' => $status );
        }

        $pagination = new \Ilch\Pagination();
        $pagination->setRowsPerPage(5);
        $pagination->setPage($this->getRequest()->getParam('page'));
        $this->getView()->set('eventList', $eventMapper->getEventList($status, $pagination) );
        $this->getView()->set('pagination', $pagination);
    }
	
    public function calenderAction()
    {
        $this->addMessage('comming soon', 'info');
        $this->getView();
    }
	
    public function treatAction()
    {		
        $user = new UserMapper;
        $eventMapper = new EventMapper();
        
        if($this->getRequest()->isPost()) {
            $model = new EventModel();
            $date = new \Ilch\Date();
            
            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $status = $this->getRequest()->getPost('status');
            $start = $this->getRequest()->getPost('start');
            $ends = $this->getRequest()->getPost('ends');
            $registrations = $this->getRequest()->getPost('registrations');
            $organizer = $this->getRequest()->getPost('organizer');
            $event = $this->getRequest()->getPost('event');
            
            if($status == '') {
                $this->addMessage('missingStatus', 'danger');
            } elseif(empty($start) /*&& preg_match('/([0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\ [0-9]{1,2}\:[0-9]{1,2}?\:[0-9]{1,2})/', $start)*/ ) {
                $this->addMessage('missingStart', 'danger');
            } elseif(empty($ends)) {
                $this->addMessage('missingEnds', 'danger');
            } elseif(empty($registrations)) {
                $this->addMessage('missingRegistrations', 'danger');
            } elseif(empty($organizer)) {
                $this->addMessage('missingOrganizer', 'danger');
            } elseif(empty($event)) {
                $this->addMessage('missingEvent', 'danger');
            } else {

                $model->setStatus($status);
                $model->setStart($start);
                $model->setEnds($ends);
                $model->setRegistrations($registrations);
                $model->setOrganizer($organizer);
                $model->setEvent($event);
                $model->setTitle($this->getRequest()->getPost('title'));
                $model->setMessage($this->getRequest()->getPost('message'));
                $model->setChanged($date);
                $model->setCreated($date);
                $eventMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }

        if ($evendId = $this->getRequest()->getParam('id')) {
            $this->getView()->set('event', $eventMapper->getEventById($evendId) );
        }
        
        $this->getView()->set('users', $user->getUserList(  ) );
        $this->getView()->set('status', json_decode($this->getConfig()->get('event_status'), true) );
        $this->getView()->set('eventNames', $eventMapper->getEventNames() );
    }
	
}
?>