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
			$this->redirect(array('module' => 'install', 'action' => 'license'));
		}
	}

	public function licenseAction()
	{
		$this->getView()->licenceText = file_get_contents(APPLICATION_PATH.'/../licence.txt');

		if($_POST)
		{
			if($this->getRequest()->getPost('licenceAccepted'))
			{
				$this->redirect(array('module' => 'install', 'action' => 'systemcheck'));
			}
			else
			{
				$this->getView()->error = true;
			}
		}
	}

	public function systemcheckAction()
	{
		$htaccessString = "RewriteEngine on\nRewriteBase ".REWRITE_BASE."/rewrite\nRewriteRule !\.(js|ico|gif|jpg|png|css|html)$ index.php\n";
		file_put_contents(APPLICATION_PATH.'/../rewrite/.htaccess', $htaccessString);

		$this->getView()->phpVersion = phpversion();

		if($_POST)
		{
			if(version_compare(phpversion(), '5.3.0', '>'))
			{
				$this->redirect(array('module' => 'install', 'action' => 'database'));
			}
		}
	}


	public function databaseAction()
	{
		foreach(array('dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix') as $name)
		{
			if(!empty($_SESSION['install'][$name]))
			{
				$this->getView()->$name = $_SESSION['install'][$name];
			}
		}

		if($_POST)
		{
			$_SESSION['install']['dbEngine'] = $this->getRequest()->getPost('dbEngine');
			$_SESSION['install']['dbHost'] = $this->getRequest()->getPost('dbHost');
			$_SESSION['install']['dbUser'] = $this->getRequest()->getPost('dbUser');
			$_SESSION['install']['dbPassword'] = $this->getRequest()->getPost('dbPassword');
			$_SESSION['install']['dbName'] = $this->getRequest()->getPost('dbName');
			$_SESSION['install']['dbPrefix'] = $this->getRequest()->getPost('dbPrefix');

			$this->redirect(array('module' => 'install', 'action' => 'config'));
		}
	}

	public function configAction()
	{
		if($_POST)
		{
			$cmsType = $this->getRequest()->getPost('cmsType');

			$rewrite = false;

			if(isset($_SESSION['install']['rewrite']))
			{
				$rewrite = true;
			}

			$config = new Ilch_Config();
			$config->setConfig('dbEngine', $_SESSION['install']['dbEngine']);
			$config->setConfig('dbHost', $_SESSION['install']['dbHost']);
			$config->setConfig('dbUser', $_SESSION['install']['dbUser']);
			$config->setConfig('dbPassword', $_SESSION['install']['dbPassword']);
			$config->setConfig('dbName', $_SESSION['install']['dbName']);
			$config->setConfig('dbPrefix', $_SESSION['install']['dbPrefix']);
			$config->setConfig('rewrite', $rewrite);
			$config->saveConfigToFile(CONFIG_PATH.'/config.php');

			$dbFactory = new Ilch_Database_Factory();
			$db = $dbFactory->getInstanceByConfig($config);

			$sqlString = file_get_contents(__DIR__.'/../files/install_'.$cmsType.'.sql');
			$queryParts = explode(';', $sqlString);

			foreach($queryParts as $query)
			{
				$db->query($query);
			}

			unset($_SESSION['install']);

			if($rewrite === true)
			{
				$htaccessString = "RewriteEngine on\nRewriteBase ".REWRITE_BASE."\nRewriteRule !\.(js|ico|gif|jpg|png|css|html)$ index.php\n";
				file_put_contents(APPLICATION_PATH.'/../.htaccess', $htaccessString);
				$this->redirect(array('module' => 'install', 'action' => 'finish'), null, true);
			}

			$this->redirect(array('module' => 'install', 'action' => 'finish'));
		}
	}

	public function finishAction()
	{
	}
}