<?php
namespace Eventplaner\Controllers\Admin;
defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as CharakterMapper;

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
                    'name' => 'list',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
				array
                (
                    'name' => 'calender',
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
                'name' => 'createNewEvent',
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
	
	public function createAction()
    {
		//$this->arPrint( $_SESSION );
	
		// Header Menu Definieren

		
		// Test
		$this->addMessage("Neuen Event erstellen!", 'info');
		
		//$charakters = new CharakterMapper;
		//$this->getView()->set('eventplaner', $charakters->getList());
    }
	
	public function arPrint( $array )
	{
		echo "<pre>";
		print_r( $array );
		echo "</pre>";
	}
}
?>