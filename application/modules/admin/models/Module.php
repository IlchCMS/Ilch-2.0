<?php
/**
 * Holds class Admin_ModuleModel.
 *
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * The module model class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Admin_ModuleModel extends Ilch_Model
{
	/**
	 * Id of the module.
	 *
	 * @var int
	 */
	protected $_id = null;

	/**
	 * Key of the module.
	 *
	 * @var string
	 */
	protected $_key = '';

	/**
	 * Names of the module.
	 *
	 * @var array
	 */
	protected $_names;

	/**
	 * Gets the id.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Sets the id.
	 *
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->_id = (int)$id;
	}

	/**
	 * Gets the key.
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->_key;
	}

	/**
	 * Sets the key.
	 *
	 * @param string $key
	 */
	public function setKey($key)
	{
		$this->_key = (string)$key;
	}

	/**
	 * Add a module name for given language.
	 *
	 * @param string $langKey
	 * @param string $name
	 */
	public function addName($langKey, $name)
	{
		$this->_names[$langKey] = (string)$name;
	}

	/**
	 * Gets module name for given language.
	 *
	 * @return string
	 */
	public function getName($langKey)
	{
		return $this->_names[$langKey];
	}

	/**
	 * Gets alle module names.
	 *
	 * @return array
	 */
	public function getNames()
	{
		return $this->_names;
	}
}