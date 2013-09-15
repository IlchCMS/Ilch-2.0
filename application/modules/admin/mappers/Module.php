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
			$moduleModel->setIconSmall($moduleRow['icon_small']);
			$nameRows = $this->getDatabase()->selectArray('*', 'modules_names', array('module_id' => $moduleRow['id']));

			foreach($nameRows as $nameRow)
			{
				$moduleModel->addName($nameRow['locale'], $nameRow['name']);
			}

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
		$moduleId = $this->getDatabase()->insert
		(
			array
			(
				'key' => $module->getKey(),
				'icon_small' => $module->getIconSmall(),
			),
			'modules'
		);

		foreach($module->getNames() as $key => $value)
		{
			$this->getDatabase()->insert
			(
				array('module_id' => $moduleId, 'locale' => $key, 'name' => $value),
				'modules_names'
			);
		}

		return $moduleId;
	}
}