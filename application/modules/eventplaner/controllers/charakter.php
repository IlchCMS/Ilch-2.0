<?php
namespace Eventplaner\Controllers;
defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as CharakterMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
		//$this->arPrint( $_SESSION );
	
		// Header Menu Definieren
		$this->getLayout()->getHmenu()->add($this->getTranslator()->trans('eventplaner'), array('action' => 'index'));
		
		// Test
		$this->addMessage($this->getTranslator()->trans('entrySuccess'), 'info');
		
		$charakters = new CharakterMapper;
		$this->getView()->set('eventplaner', $charakters->getList());
    }
}
?>