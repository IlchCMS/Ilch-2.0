<?php
/**
 * @copyright Ilch 2.0
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
        $modulesRows = $this->db()->select()
            ->fields(['m.key', 'm.system', 'm.layout', 'm.hide_menu', 'm.version', 'm.link', 'm.icon_small', 'm.author'])
            ->from(['m' => 'modules'])
            ->join(['c' => 'modules_content'], 'm.key = c.key', 'LEFT', ['c.locale', 'c.description', 'c.name'])
            ->where(['c.locale' => $this->db()->escape(\Ilch\Registry::get('translator')->getLocale())])
            ->order(['c.name' => 'ASC'])
            ->execute()
            ->fetchRows();

        $modules = [];
        foreach ($modulesRows as $moduleRow) {
            $moduleModel = new ModuleModel();
            $moduleModel->setKey($moduleRow['key']);
            $moduleModel->setSystemModule($moduleRow['system']);
            $moduleModel->setLayoutModule($moduleRow['layout']);
            $moduleModel->setHideMenu($moduleRow['hide_menu']);
            $moduleModel->setVersion($moduleRow['version']);
            $moduleModel->setLink($moduleRow['link']);
            $moduleModel->setIconSmall($moduleRow['icon_small']);
            $moduleModel->setAuthor($moduleRow['author']);
            $moduleModel->addContent($moduleRow['locale'], ['name' => $moduleRow['name'], 'description' => $moduleRow['description']]);

            $modules[] = $moduleModel;
        }

        return $modules;
    }

    /**
     * Gets all not installed modules.
     *
     * @return ModuleModel[]|Array[]
     */
    public function getModulesNotInstalled()
    {
        foreach (glob(APPLICATION_PATH.'/modules/*') as $modulePath) {
            if (is_dir($modulePath)) {
                $moduleModel = new ModuleModel();
                $moduleModel->setKey(basename($modulePath));
                $modulesDir[] = $moduleModel->getKey();
            }
        }

        $removeModule = ['admin', 'install', 'sample', 'error'];
        $modulesDir = array_diff($modulesDir, $removeModule);

        foreach ($this->getModules() as $module) {
            $moduleModel = new ModuleModel();
            $moduleModel->setKey($module->getKey());

            $modulesDB[] = $moduleModel->getKey();
        }

        $modulesNotInstalled = array_diff($modulesDir, $modulesDB);

        if (empty($modulesNotInstalled)) {
            return [];
        }

        foreach ($modulesNotInstalled as $module) {
            $moduleModel = new ModuleModel();
            $configClass = '\\Modules\\'.ucfirst($module).'\\Config\\Config';
            $config = new $configClass();

            $moduleModel->setKey($config->config['key']);
            $moduleModel->setIconSmall($config->config['icon_small']);
            $moduleModel->setVersion($config->config['version']);

            if (isset($config->config['link'])) {
                $moduleModel->setLink($config->config['link']);
            }
            if (isset($config->config['author'])) {
                $moduleModel->setAuthor($config->config['author']);
            }
            if (isset($config->config['languages'])) {
                foreach ($config->config['languages'] as $key => $value) {
                    $moduleModel->addContent($key, $value);
                }
            }
            $moduleModel->setIlchCore($config->config['ilchCore']);
            $moduleModel->setPHPVersion($config->config['phpVersion']);
            if (isset($config->config['phpExtensions'])) {
                $moduleModel->setPHPExtension($config->config['phpExtensions']);
            }
            if (isset($config->config['depends'])) {
                $moduleModel->setDepends($config->config['depends']);
            } else {
                $moduleModel->setDepends([]);
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
     * @return ModuleModel|void
     */
    public function getModulesByKey($key, $locale)
    {
        $modulesRows = $this->db()->select('*')
            ->from('modules_content')
            ->where(['key' => $key, 'locale' => $locale])
            ->execute()
            ->fetchAssoc();

        if (empty($modulesRows)) {
            return;
        }

        $modulesModel = new ModuleModel();
        $modulesModel->setName($modulesRows['name']);

        return $modulesModel;
    }

    /**
     * Get module with given key.
     *
     * @param string $key
     * @return ModuleModel|null
     */
    public function getModuleByKey($key) {
        $moduleRow = $this->db()->select('*')
            ->from('modules')
            ->where(['key' => $key])
            ->execute()
            ->fetchAssoc();

        if (empty($moduleRow)) {
            return null;
        }

        $moduleModel = new ModuleModel();
        $moduleModel->setKey($moduleRow['key']);
        $moduleModel->setSystemModule($moduleRow['system']);
        $moduleModel->setLayoutModule($moduleRow['layout']);
        $moduleModel->setHideMenu($moduleRow['hide_menu']);
        $moduleModel->setVersion($moduleRow['version']);
        $moduleModel->setLink($moduleRow['link']);
        $moduleModel->setIconSmall($moduleRow['icon_small']);
        $moduleModel->setAuthor($moduleRow['author']);

        return $moduleModel;
    }

    /**
     * Gets an array of keys of the installed modules.
     *
     * @return array|string[]
     */
    public function getKeysInstalledModules()
    {
        $modulesRows = $this->db()->select('key')
            ->from('modules')
            ->execute()
            ->fetchList();

        if (empty($modulesRows)) {
            return [];
        }

        return $modulesRows;
    }

    public function getVersionsOfModules() {
        $modulesRows = $this->db()->select(['key','version'])
            ->from('modules')
            ->execute()
            ->fetchRows('key');

        $modulesNotInstalled = $this->getModulesNotInstalled();

        foreach ($modulesNotInstalled as $moduleNotInstalled) {
            $key = $moduleNotInstalled->getKey();
            $modulesRows[$key] = array('key' => $key, 'version' => $moduleNotInstalled->getVersion());
        }

        if (empty($modulesRows)) {
            return [];
        }

        return $modulesRows;
    }

    /**
     * Update the version of a module in the database.
     *
     * @param string $key
     * @param string $version
     *
     */
    public function updateVersion($key, $version) {
        $this->db()->update('modules')
            ->values(['version' => $version])
            ->where(['key' => $key])
            ->execute();
    }

    /**
     * Inserts a module model in the database.
     *
     * @param ModuleModel $module
     * @return int
     */
    public function save(ModuleModel $module)
    {
        $moduleId = $this->db()->insert('modules')
            ->values([
                'key' => $module->getKey(),
                'system' => (int) $module->getSystemModule(),
                'layout' => (int) $module->getLayoutModule(),
                'hide_menu' => (int) $module->getHideMenu(),
                'icon_small' => $module->getIconSmall(),
                'version' => $module->getVersion(),
                'link' => $module->getLink(),
                'author' => $module->getAuthor()
            ])
            ->execute();

        foreach ($module->getContent() as $key => $value) {
            $this->db()->insert('modules_content')
                ->values([
                    'key' => $module->getKey(),
                    'locale' => $key,
                    'name' => $value['name'],
                    'description' => $value['description']
                ])
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
            ->where(['key' => $key])
            ->execute();

        $this->db()->delete('modules_content')
            ->where(['key' => $key])
            ->execute();

        $this->db()->delete('modules_boxes_content')
            ->where(['module' => $key])
            ->execute();
    }
}
