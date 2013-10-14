<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Mapper
{
	/**
	 * Hold the database adapter.
	 *
	 * @var Ilch_Database_*
	 */
	private $_db;

	/**
	 * Injects the database adapter to the mapper.
	 */
	public function __construct()
	{
		$this->_db = Ilch_Registry::get('db');
	}

	/**
	 * Gets the database adapter.
	 *
	 * @return Ilch_Database_*
	 */
	public function getDatabase()
	{
		return $this->_db;
	}

	/**
	 * Sets the database adapter.
	 *
	 * @param Ilch_Database_*
	 */
	public function setDatabase($db)
	{
		$this->_db = $db;
	}
}