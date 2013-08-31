<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Config
{
	/**
	 * @var array
	 */
	protected $_configData;

	/**
	 * Gets the config for given key.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	public function getConfig($key)
	{
		if(isset($this->_configData[$key]))
		{
			return $this->_configData[$key];
		}

		return null;
	}

	/**
	 * Sets the config for given key/vale.
	 *
	 * @param string $key
	 * @param string|integer $value
	 */
	public function setConfig($key, $value)
	{
		$this->_configData[$key] = $value;
	}

	/**
	 * Saves the whole config in a file.
	 * 
	 * @param string $fileName
	 */
	public function saveConfigToFile($fileName)
	{
		$fileString = '<?php';
		$fileString .= "\n";

		foreach($this->_configData as $key => $value)
		{
			$fileString .= '$config["'.$key.'"] = "'.$value.'";';
			$fileString .= "\n";
		}

		$fileString .= '?>';
		file_put_contents($fileName, $fileString);
	}
	
	/**
	 * Loads the config from the given path.
	 * 
	 * @param string $fileName
	 */
	public function loadConfigFromFile($fileName)
	{
		require_once $fileName;

		foreach($config as $key => $value)
		{
			$this->setConfig($key, $value);
		}
	}
}