<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
 
namespace Eventplaner\Controllers;
defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as EventMapper;
use Eventplaner\Models\Eventplaner as EventModel;
use User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {    
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('eventplaner'), array('action' => 'index'));
        
        $eventMapper = new EventMapper();

        if( $status = $this->getRequest()->getParam('status') ){
            $status = array('status' => $status );
        }

        $pagination = new \Ilch\Pagination();
        $pagination->setRowsPerPage(10);
        $pagination->setPage($this->getRequest()->getParam('page'));
        $this->getView()->set('eventList', $eventMapper->getEventList($status, $pagination) );
        $this->getView()->set('pagination', $pagination);
    }
    
    public function detailsAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('eventplaner'), array('action' => 'index'));
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('eventDetails'), array('action' => 'details', 'id' => $this->getRequest()->getParam('id')));
        
        $user = new UserMapper;
        $eventMapper = new EventMapper();
        
        if ($evendId = $this->getRequest()->getParam('id')) {
            $this->getView()->set('event', $eventMapper->getEventById($evendId) );
        }

        $this->getView()->set('users', $user->getUserList(  ) );
        //$this->getView()->set('status', $this->getStatusArray() );
        $this->getView()->set('eventNames', $eventMapper->getEventNames() );
    }
}
?>