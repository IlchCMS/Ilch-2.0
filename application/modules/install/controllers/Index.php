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
		/*
		 * Dont set a time limit for installer.
		 */
		set_time_limit(0);

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

		if($this->getRequest()->isPost())
		{	
			$_SESSION['install']['timezone'] = $this->getRequest()->getPost('timezone');
			$this->redirect(array('module' => 'install', 'action' => 'license'));
		}
		
		if(!empty($_SESSION['install']['timezone']))
		{
			$this->getView()->timezone = $_SESSION['install']['timezone'];
		}
		else
		{
			$this->getView()->timezone = SERVER_TIMEZONE;
		}

		$this->getView()->timezones = DateTimeZone::listIdentifiers();
	}

	public function licenseAction()
	{
		$this->getView()->licenceText = file_get_contents(APPLICATION_PATH.'/../licence.txt');

		if($this->getRequest()->isPost())
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
		$errors = array();
		$this->getView()->phpVersion = phpversion();

		if(!version_compare(phpversion(), '5.3.0', '>'))
		{
			$errors['version'] = true;
		}

		if(!is_writable(CONFIG_PATH))
		{
			$errors['writableConfig'] = true;
		}

		if(!is_writable(APPLICATION_PATH.'/../.htaccess'))
		{
			$errors['writableHtaccess'] = true;
		}

		if($this->getRequest()->isPost() && empty($errors))
		{
			$this->redirect(array('module' => 'install', 'action' => 'database'));
		}
	}

	public function databaseAction()
	{
		$errors = array();

		if($this->getRequest()->isPost())
		{
			$_SESSION['install']['dbEngine'] = $this->getRequest()->getPost('dbEngine');
			$_SESSION['install']['dbHost'] = $this->getRequest()->getPost('dbHost');
			$_SESSION['install']['dbUser'] = $this->getRequest()->getPost('dbUser');
			$_SESSION['install']['dbPassword'] = $this->getRequest()->getPost('dbPassword');
			$_SESSION['install']['dbName'] = $this->getRequest()->getPost('dbName');
			$_SESSION['install']['dbPrefix'] = $this->getRequest()->getPost('dbPrefix');

			$ilch = new Ilch_Database_Factory();
			$db = $ilch->getInstanceByEngine($this->getRequest()->getPost('dbEngine'));
			$dbConnect = $db->connect($this->getRequest()->getPost('dbHost'), $this->getRequest()->getPost('dbUser'), $this->getRequest()->getPost('dbPassword'));

			if(!$dbConnect)
			{
				$errors['dbConnection'] = 'dbConnectionError';
			}

			if($dbConnect && !$db->setDatabase($this->getRequest()->getPost('dbName')))
			{
				$errors['dbDatabase'] = 'dbDatabaseError';
			}

			if(empty($errors))
			{
				$this->redirect(array('module' => 'install', 'action' => 'config'));
			}

			$this->getView()->errors = $errors;
		}

		foreach(array('dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix') as $name)
		{
			if(!empty($_SESSION['install'][$name]))
			{
				$this->getView()->$name = $_SESSION['install'][$name];
			}
		}
	}

	public function configAction()
	{
		$errors = array();

		if($this->getRequest()->isPost())
		{
			$_SESSION['install']['adminName'] = $this->getRequest()->getPost('adminName');
			$_SESSION['install']['adminPassword'] = $this->getRequest()->getPost('adminPassword');
			$_SESSION['install']['adminPassword2'] = $this->getRequest()->getPost('adminPassword2');
			$_SESSION['install']['adminEmail'] = $this->getRequest()->getPost('adminEmail');
			$_SESSION['install']['cmsType'] = $this->getRequest()->getPost('cmsType');

			if(empty($_SESSION['install']['adminName']))
			{
				$errors['adminName'] = 'fieldEmpty';
			}

			if(empty($_SESSION['install']['adminPassword']))
			{
				$errors['adminPassword'] = 'fieldEmpty';
			}

			if($_SESSION['install']['adminPassword'] !== $_SESSION['install']['adminPassword2'])
			{
				$errors['adminPassword2'] = 'fieldDiffersPassword';
			}

			if(empty($_SESSION['install']['adminEmail']))
			{
				$errors['adminEmail'] = 'fieldEmpty';
			}
			elseif(!filter_var($_SESSION['install']['adminEmail'], FILTER_VALIDATE_EMAIL))
			{
				$errors['adminEmail'] = 'fieldEmail';
			}

			if(empty($errors))
			{
				$fileConfig = new Ilch_Config_File();
				$fileConfig->set('dbEngine', $_SESSION['install']['dbEngine']);
				$fileConfig->set('dbHost', $_SESSION['install']['dbHost']);
				$fileConfig->set('dbUser', $_SESSION['install']['dbUser']);
				$fileConfig->set('dbPassword', $_SESSION['install']['dbPassword']);
				$fileConfig->set('dbName', $_SESSION['install']['dbName']);
				$fileConfig->set('dbPrefix', $_SESSION['install']['dbPrefix']);
				$fileConfig->saveConfigToFile(CONFIG_PATH.'/config.php');

				$dbFactory = new Ilch_Database_Factory();
				$db = $dbFactory->getInstanceByConfig($fileConfig);
				Ilch_Registry::set('db', $db);

				$sqlString = file_get_contents(__DIR__.'/../files/install_general.sql');
				$queryParts = explode(';', $sqlString);

				foreach($queryParts as $query)
				{
					$db->query($query);
				}

				$userMapper = new User_UserMapper();
				$user = new User_UserModel();
				$user->setName($_SESSION['install']['adminName']);
				$user->setPassword(crypt($_SESSION['install']['adminPassword']));
				$user->setEmail($_SESSION['install']['adminEmail']);
				$user->setGroups(array(1));
				$userMapper->save($user);

				$date = new DateTime();
				$databaseConfig = new Ilch_Config_Database($db);
				$databaseConfig->set('version', VERSION, 1);
				$databaseConfig->set('locale', $this->getTranslator()->getLocale(), 1);
				$databaseConfig->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1);
				$databaseConfig->set('timezone', $_SESSION['install']['timezone']);

				unset($_SESSION['install']);

				$this->redirect(array('module' => 'install', 'action' => 'finish'));
			}

			$this->getView()->errors = $errors;
		}

		foreach(array('adminName', 'adminPassword', 'adminPassword2', 'adminEmail') as $name)
		{
			if(!empty($_SESSION['install'][$name]))
			{
				$this->getView()->$name = $_SESSION['install'][$name];
			}
		}
	}

	public function finishAction()
	{
	}
}