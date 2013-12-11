<?php
/**
 * Holds Admin_ModuleMapper.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Mappers;
defined('ACCESS') or die('no direct access');

/**
 * The module mapper class.
 *
 * @package ilch
 */
class Module extends \Ilch\Mapper
{
    /**
     * Gets all modules.
     *
     * @return null|Admin_ModuleModel[]
     */
    public function getModules()
    {
        $modules = array();
        $modulesRows = $this->db()->selectArray
        (
            '*',
            'modules'
        );

        if (empty($modulesRows)) {
            return null;
        }

        foreach ($modulesRows as $moduleRow) {
            $moduleModel = new \Admin\Models\Module();
            $moduleModel->setId($moduleRow['id']);
            $moduleModel->setKey($moduleRow['key']);
            $moduleModel->setIconSmall($moduleRow['icon_small']);
            $nameRows = $this->db()->selectArray('*', 'modules_names', array('module_id' => $moduleRow['id']));

            foreach ($nameRows as $nameRow) {
                $moduleModel->addName($nameRow['locale'], $nameRow['name']);
            }

            $modules[] = $moduleModel;
        }

        return $modules;
    }
    /**
     * Inserts a module model in the database.
     *
     * @param \Admin\Models\Module $module
     */
    public function save(\Admin\Models\Module $module)
    {
        $moduleId = $this->db()->insert
        (
            array
            (
                'key' => $module->getKey(),
                'icon_small' => $module->getIconSmall(),
            ),
            'modules'
        );

        foreach ($module->getNames() as $key => $value) {
            $this->db()->insert
            (
                array('module_id' => $moduleId, 'locale' => $key, 'name' => $value),
                'modules_names'
            );
        }

        return $moduleId;
    }
}
