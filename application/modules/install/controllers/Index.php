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

    public function systemcheckAction()
    {
	if($_POST)
	{
	    $this->redirect('install', 'index', 'database');
	}
    }

    public function licenseAction()
    {
	if($_POST)
	{
	    $this->redirect('install', 'index', 'systemcheck');
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