<?php
namespace Eventplaner\Controllers\Admin;

defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as EventMapper;
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
                    'name' => 'calenderView',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'calender'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'createEvent',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'createEvent'))
            )
        );
    }
	
    public function indexAction()
    {
		$eventMapper = new EventMapper();
		$this->getView()->set('eventList', $eventMapper->getEventList());
		//$this->arPrint( $eventMapper->getEventList() );
    }
	
	public function calenderAction()
	{
		$this->addMessage('comming soon', 'info');
		$this->getView();
	}
	
	public function createEventAction()
    {		
		$user = new UserMapper;
		//$this->arPrint( $user->getUserList( array("id" => 1) ) );
		$this->getView()->set('users', $user->getUserList(  ) );
		$this->getView()->set('status', $this->getStatusArray());
    }
	
	public function getStatusArray()
	{
		return array(
			0 => $this->getTranslator()->trans('active'),
			1 => $this->getTranslator()->trans('closed'),
			2 => $this->getTranslator()->trans('canceled')
		);
	}
	
	public function arPrint( $array )
	{
		echo "<pre>";
		print_r( $array );
		echo "</pre>";
	}
}
?>