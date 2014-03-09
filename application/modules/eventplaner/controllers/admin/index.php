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
		//$this->arPrint( $_SESSION );
	
		// Header Menu Definieren

		
		// Test
		$this->addMessage($this->getTranslator()->trans('entrySuccess'), 'info');
		
		//$charakters = new CharakterMapper;
		//$this->getView()->set('eventplaner', $charakters->getList());
    }
	
	public function calenderAction()
	{
		$this->addMessage($this->getTranslator()->trans('calender'), 'info');
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