<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Install_IndexController extends Ilch_Controller
{
    public function init()
    {
		if(isset($_SESSION['language']))
		{
			$this->getTranslator()->setLocale($_SESSION['language']);
		}
		
		$menu = array
		(
			'index' => array
			(
				'langKey' => 'menuWelcomeAndLanguage'
			),
			'license' => array
			(
				'langKey' => 'menuLicence'
			),
			'systemcheck' => array
			(
				'langKey' => 'menuSystemCheck'
			),
			'database' => array
			(
				'langKey' => 'menuDatabase'
			),
			'config' => array
			(
				'langKey' => 'menuConfig'
			),
			'finish' => array
			(
				'langKey' => 'menuFinish'
			),
		);

		foreach($menu as $key => $values)
		{
			if($this->getRequest()->getActionName() === $key)
			{
				break;
			}

			$menu[$key]['done'] = true;
		}

		$this->getLayout()->menu = $menu;
    }

    public function indexAction()
    {
		$languages = array
		(
			'en_EN' => 'English',
			'de_DE' => 'German'
		);

		$this->getView()->languages = $languages;
		$local = $this->getRequest()->getQuery('language');

		if($local)
		{
			$this->getTranslator()->setLocale($local);
			$_SESSION['language'] = $local;
		}

		if($_POST)
		{
			$this->redirect('install', 'index', 'license');
		}
    }

    public function licenseAction()
    {
		$this->getView()->licenceText = file_get_contents(APPLICATION_PATH.'/../licence.txt');

		if($_POST)
		{
			if($this->getRequest()->getPost('licenceAccepted'))
			{
				$this->redirect('install', 'index', 'systemcheck');
			}
			else
			{
				$this->getView()->error = true;
			}
		}
    }

    public function systemcheckAction()
    {
		if($_POST)
		{
				$this->redirect('install', 'index', 'database');
		}
    }


    public function databaseAction()
    {
		if($_POST)
		{
			$this->redirect('install', 'index', 'config');
		}
    }

    public function configAction()
    {
		if($_POST)
		{
			$this->redirect('install', 'index', 'finish');
		}
    }

    public function finishAction()
    {
		if($_POST)
		{
			// finish
		}
    }
}