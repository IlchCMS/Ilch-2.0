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
	 * Gets all modules.
	 *
	 * @return null|Admin_ModuleModel[]
	 */
	public function getModules()
	{
		$modules = array();
		$modulesRows = $this->getDatabase()->selectArray
		(
			'*',
			'modules'
		);

		if(empty($modulesRows))
		{
			return null;
		}

		foreach($modulesRows as $moduleRow)
		{
			$moduleModel = new Admin_ModuleModel();
			$moduleModel->setId($moduleRow['id']);
			$moduleModel->setKey($moduleRow['key']);
			$modules[] = $moduleModel;
		}

		return $modules;
	}
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
			'modules'
		);

		return $userId;
	}
}