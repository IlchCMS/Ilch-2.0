<?php
/**
 * Holds class PHPUnit_Ilch_TestCase.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Base class for test cases for Ilch.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class PHPUnit_Ilch_TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * A data array which will be used to create a config object for the registry.
	 *
	 * @var Array
	 */
	protected $_configData = array();

	/**
	 * Filling the config object with individual testcase data.
	 */
	public function setUp()
	{
		if(!Ilch_Registry::has('config') && file_exists(__DIR__.'/../../config.php'))
		{
		    $config = new Ilch_Config_File();
		    $config->loadConfigFromFile(__DIR__.'/../../config.php');
		    Ilch_Registry::set('config', $config);
		}

		$config = Ilch_Registry::get('config');

		foreach($this->_configData as $configKey => $configValue)
		{
			$config->set($configKey, $configValue);
		}
	}

	/**
	 * Returns the _files folder path for this test.
	 *
	 * @return string |false
	 * @throws Exception If the _files directory doesn`t exist.
	 */
	protected function _getFilesFolder()
	{
		$classname = get_class($this);
		/*
		 * Generating the path from tests/ to the _files folder using the classname.
		 * With the Classname Libraries_Ilch_ConfigTest the path would be "libraries/ilch".
		 */
		$classPathPart = str_replace('_', '/', $classname);
		$classPathPart = strtolower(dirname($classPathPart));

		$filesDir = APPLICATION_PATH.'/../tests/'.$classPathPart.'/_files';

		if(!is_dir($filesDir))
		{
			throw new Exception('_files directory "'.$filesDir.'" does not exist.');
		}

		return $filesDir;
	}
}