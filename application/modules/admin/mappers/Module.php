<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

/**
 * The module mapper class.
 */
class Module extends \Ilch\Mapper
{
    /**
     * Gets all modules.
     *
     * @return array|Admin_ModuleModel[]
     */
    public function getModules()
    {
        $modules = array();
        $modulesRows = $this->db()->select('*')
            ->from('modules')
            ->execute()
            ->fetchRows();

        foreach ($modulesRows as $moduleRow) {
            $moduleModel = new \Modules\Admin\Models\Module();
            $moduleModel->setKey($moduleRow['key']);
            $moduleModel->setAuthor($moduleRow['author']);
            $moduleModel->setSystemModule($moduleRow['system']);
            $moduleModel->setIconSmall($moduleRow['icon_small']);
            $contentRows = $this->db()->select('*')
                ->from('modules_content')
                ->where(array('key' => $moduleRow['key']))
                ->execute()
                ->fetchRows();

            foreach ($contentRows as $contentRow) {
                $moduleModel->addContent($contentRow['locale'], array('name' => $contentRow['name'], 'description' => $contentRow['description']));
            }

            $modules[] = $moduleModel;
        }

        return $modules;
    }

    public function getModulesByKey($key, $locale)
    {
        $modulesRows = $this->db()->select('*')
            ->from('modules_content')
            ->where(array('key' => $key, 'locale' => $locale))
            ->execute()
            ->fetchAssoc();

        if (empty($modulesRows)) {
            return null;
        }

        $modulesModel = new \Modules\Admin\Models\Module();
        $modulesModel->setName($modulesRows['name']);

        return $modulesModel;
    }

    /**
     * Inserts a module model in the database.
     *
     * @param \Modules\Admin\Models\Module $module
     */
    public function save(\Modules\Admin\Models\Module $module)
    {
        $moduleId = $this->db()->insert('modules')
            ->values(array('key' => $module->getKey(), 'system' => $module->getSystemModule(),
                'icon_small' => $module->getIconSmall(), 'author' => $module->getAuthor()))
            ->execute();

        foreach ($module->getContent() as $key => $value) {
            $this->db()->insert('modules_content')
                ->values(array('key' => $module->getKey(), 'locale' => $key, 'name' => $value['name'], 'description' => $value['description']))
                ->execute();
        }

        return $moduleId;
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        $menuMapper = new \Modules\Admin\Mappers\Menu();
        $menuMapper->deleteItemsByModuleKey($key);
        $this->db()->delete('modules')
            ->where(array('key' => $key))
            ->execute();
        
        $this->db()->delete('modules_content')
            ->where(array('key' => $key))
            ->execute();
    }
}
