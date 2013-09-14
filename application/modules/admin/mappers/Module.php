<?php
/**
 * Holds Admin_ModuleMapper.
 *
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * The module mapper class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Admin_ModuleMapper extends Ilch_Mapper
{
	/**
	 * Inserts a module model in the database.
	 *
	 * @param Admin_ModuleModel $module
	 */
	public function save(Admin_ModuleModel $module)
	{
		$userId = $this->getDatabase()->insert
		(
			array('key' => $module->getKey()),
			'module'
		);

		return $userId;
	}
}