<?php
namespace Eventplaner\Controllers\Admin;
defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as EventMapper;

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
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'create'))
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
	
	public function createAction()
    {
		$this->addMessage($this->getTranslator()->trans('entrySuccess'), 'info');
		//$this->getView()->set('test', 'Hallo Welt');
    }
	
	public function arPrint( $array )
	{
		echo "<pre>";
		print_r( $array );
		echo "</pre>";
	}
}
?>