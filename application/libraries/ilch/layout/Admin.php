<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Layout_Admin extends Ilch_Layout_Base
{
	/**
	 * @var array
	 */
	private $_menus = array();
	
	/**
	 * @var array
	 */
	private $_menuActions = array();

	/**
	 * @return array
	 */
	public function getMenus()
	{
		return $this->_menus;
	}

	/**
	 * Add menu to layout.
	 *
	 * @param string $headKey
	 * @param array $items
	 */
	public function addMenu($headKey, $items)
	{
		$this->_menus[$headKey] = $items;
 	}

	/**
	 * Add menu action to layout.
	 *
	 * @param array $actionArray
	 */
	public function addMenuAction($actionArray)
	{
		$this->_menuActions[] = $actionArray;
	}

	/**
	 * @return array
	 */
	public function getMenuAction()
	{
		return $this->_menuActions;
	}
}