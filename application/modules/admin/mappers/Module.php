<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Module as ModuleModel;
use Modules\Admin\Mappers\Menu as MenuMapper;

class Module extends \Ilch\Mapper
{
    /**
     * Gets all modules.
     *
     * @return array|ModuleModel[]
     */
    public function getModules()
    {
        $modules = array();
        $modulesRows = $this->db()->select('*')
            ->from('modules')
            ->execute()
            ->fetchRows();

        foreach ($modulesRows as $moduleRow) {
            $moduleModel = new ModuleModel();
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

    /**
     * Gets all not installed modules.
     *
     * @param string $locale
     */
    public function getModulesNotInstalled($locale)
    {
        foreach (glob(APPLICATION_PATH.'/modules/*') as $modulePath) {
            $moduleModel = new ModuleModel();
            $moduleModel->setKey(basename($modulePath));

            $modulesDir[] = $moduleModel->getKey();
        }
        $removeModule = array('admin', 'install', 'sample');
        $modulesDir = array_diff($modulesDir, $removeModule);

        foreach ($this->getModules() as $module) {
            $moduleModel = new ModuleModel();
            $moduleModel->setKey($module->getKey());

            $modulesDB[] = $moduleModel->getKey();
        }

        $modulesNotInstalled = array_diff($modulesDir, $modulesDB);

        if (empty($modulesNotInstalled)) {
            return null;
        }

        foreach ($modulesNotInstalled as $module) {
            $moduleModel = new ModuleModel();
            $configClass = '\\Modules\\'.ucfirst($module).'\\Config\\config';
            $config = new $configClass($locale);

            $moduleModel->setKey($config->config['key']);
            $moduleModel->setIconSmall($config->config['icon_small']);

            if (isset($config->config['author'])) {
                $moduleModel->setAuthor($config->config['author']);
            }

            if (isset($config->config['languages'])) {
                foreach ($config->config['languages'] as $key => $value) {
                    $moduleModel->addContent($key, $value);
                }
            }

            $modules[] = $moduleModel;
        }

        return $modules;
    }

    /**
     * Gets modules with given key and locale.
     *
     * @param string $key
     * @param string $locale
     */
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

        $modulesModel = new ModuleModel();
        $modulesModel->setName($modulesRows['name']);

        return $modulesModel;
    }

    /**
     * Inserts a module model in the database.
     *
     * @param ModuleModel $module
     */
    public function save(ModuleModel $module)
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
     * Deletes a given module with the given key.
     * 
     * @param string $key
     */
    public function delete($key)
    {
        $menuMapper = new MenuMapper();
        $menuMapper->deleteItemsByModuleKey($key);
        $this->db()->delete('modules')
            ->where(array('key' => $key))
            ->execute();
        
        $this->db()->delete('modules_content')
            ->where(array('key' => $key))
            ->execute();
    }
}
