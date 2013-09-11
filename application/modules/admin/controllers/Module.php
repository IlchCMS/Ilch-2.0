<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Admin_ModuleController extends Ilch_Controller
{
	public function init()
	{
		$menu = array();
		$this->getLayout()->set('menu', $menu);
	}

	public function loadAction()
	{
		$modulName = $this->getRequest()->getQuery('submodule');
		$controllerName = $this->getRequest()->getQuery('subcontroller');
		$actionName = $this->getRequest()->getQuery('subaction');

		if(empty($controllerName))
		{
			$controllerName = 'index';
		}
		
		if(empty($actionName))
		{
			$actionName = 'index';
		}
		
		$action = $actionName.'Action';

		$view = new Ilch_View($this->getRequest(), $this->getTranslator(), $this->getRouter());
		$controller = ucfirst($modulName).'_Admin_'.ucfirst($controllerName).'Controller';
		$controller = new $controller($this->getLayout(), $view, $this->getRequest(), $this->getRouter(), $this->getTranslator());
		$viewOutput = $view->loadScript(APPLICATION_PATH.'/modules/'.$modulName.'/views/admin/'.$controllerName.'/'.$actionName.'.php');

		if(method_exists($controller, 'init'))
		{
			$controller->init();
		}

		if(method_exists($controller, $action))
		{
			$controller->$action();
		}
		else
		{
			throw new InvalidArgumentException('action "'.$action.'" not known');
		}
		
		$this->getTranslator()->load(APPLICATION_PATH.'/modules/'.$modulName.'/translations');

		$this->getLayout()->setContent($viewOutput);
	}
}