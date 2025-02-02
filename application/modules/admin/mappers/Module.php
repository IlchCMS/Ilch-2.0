<?php

/**
 * @copyright Ilch 2
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Module as ModuleModel;
use Modules\Admin\Mappers\Menu as MenuMapper;

class Module extends \Ilch\Mapper
{
    protected $cache = [];

    /**
     * Gets all modules.
     *
     * @return array|ModuleModel[]
     */
    public function getModules(): array
    {
        $modulesRows = $this->db()->select()
            ->fields(['m.key', 'm.system', 'm.layout', 'm.hide_menu', 'm.version', 'm.link', 'm.icon_small', 'm.author'])
            ->from(['m' => 'modules'])
            ->join(['c' => 'modules_content'], 'm.key = c.key', 'LEFT', ['c.locale', 'c.description', 'c.name'])
            ->join(['ex' => 'modules_php_extensions'], 'm.key = ex.key', 'LEFT', ['phpExtensions' => 'GROUP_CONCAT(ex.extension)'])
            ->join(['lr' => 'modules_folderrights'], 'm.key = lr.key', 'LEFT', ['folderRights' => 'GROUP_CONCAT(lr.folder)'])
            ->where(['c.locale' => $this->db()->escape(\Ilch\Registry::get('translator')->getLocale())])
            ->order(['c.name' => 'ASC'])
            ->group(['m.key'])
            ->execute()
            ->fetchRows();

        $modules = [];
        foreach ($modulesRows as $moduleRow) {
            $moduleRow['phpExtensions'] = explode(',', $moduleRow['phpExtensions']);
            $moduleRow['folderRights'] = explode(',', $moduleRow['folderRights']);

            $moduleModel = new ModuleModel();
            $moduleModel->setByArray($moduleRow);

            $moduleModel->addContent($moduleRow['locale'], ['name' => $moduleRow['name'], 'description' => $moduleRow['description']]);

            $modules[] = $moduleModel;
            $this->cache[$moduleRow['key']] = $moduleModel;
        }

        return $modules;
    }

    /**
     * Gets all modules.
     *
     * @return array
     * @since 2.2.8
     */
    public function getLocalModules(): array
    {
        $modules = [];
        foreach (glob(ROOT_PATH . '/application/modules/*') as $modulesPath) {
            if (is_dir($modulesPath)) {
                $key = basename($modulesPath);

                $configClass = '\\Modules\\' . ucfirst($key) . '\\Config\\Config';
                if (class_exists($configClass)) {
                    $modules[] = $key;
                }
            }
        }

        return $modules;
    }

    /**
     * Gets all not installed modules.
     *
     * @return ModuleModel[]|[]
     */
    public function getModulesNotInstalled(): array
    {
        $modulesDir = $this->getLocalModules();

        $removeModule = ['admin', 'install', 'sample', 'error'];
        $modulesDir = array_diff($modulesDir, $removeModule);

        $modulesDB = [];
        foreach ($this->getModules() as $module) {
            $moduleModel = new ModuleModel();
            $moduleModel->setKey($module->getKey());

            $modulesDB[] = $moduleModel->getKey();
        }

        $modulesNotInstalled = array_diff($modulesDir, $modulesDB);

        if (empty($modulesNotInstalled)) {
            return [];
        }

        $modules = [];
        foreach ($modulesNotInstalled as $module) {
            $moduleModel = new ModuleModel();
            $configClass = '\\Modules\\' . ucfirst($module) . '\\Config\\Config';
            $config = new $configClass();

            $moduleModel->setByArray($config->config);

            $modules[] = $moduleModel;
        }

        return $modules;
    }

    /**
     * Gets modules with given key and locale.
     *
     * @param string $key
     * @param string $locale
     * @return ModuleModel|null
     */
    public function getModulesByKey(string $key, string $locale): ?ModuleModel
    {
        $modulesRows = $this->db()->select('*')
            ->from('modules_content')
            ->where(['key' => $key, 'locale' => $locale])
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
     * Get module with given key.
     *
     * @param string $key
     * @param bool $force
     * @return ModuleModel|null
     */
    public function getModuleByKey(string $key, bool $force = false): ?ModuleModel
    {
        if (isset($this->cache[$key]) && !$force) {
            return $this->cache[$key];
        }

        $moduleRow = $this->db()->select('*')
            ->from('modules')
            ->where(['key' => $key])
            ->execute()
            ->fetchAssoc();

        if (empty($moduleRow)) {
            return null;
        }

        $moduleModel = new ModuleModel();
        $moduleModel->setByArray($moduleRow);

        $this->cache[$key] = $moduleModel;

        return $moduleModel;
    }

    /**
     * Gets an array of keys of the installed modules.
     *
     * @return array|string[]
     */
    public function getKeysInstalledModules(): array
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

    /**
     * Get the version numbers of the modules.
     *
     * @return array
     */
    public function getVersionsOfModules(): array
    {
        $modulesRows = $this->db()->select(['key','version'])
            ->from('modules')
            ->execute()
            ->fetchRows('key');

        $modulesNotInstalled = $this->getModulesNotInstalled();

        foreach ($modulesNotInstalled as $moduleNotInstalled) {
            $key = $moduleNotInstalled->getKey();
            $modulesRows[$key] = ['key' => $key, 'version' => $moduleNotInstalled->getVersion()];
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
    public function updateVersion(string $key, string $version)
    {
        $this->db()->update('modules')
            ->values(['version' => $version])
            ->where(['key' => $key])
            ->execute();
    }

    /**
     * Inserts a module model in the database.
     *
     * @param ModuleModel $module
     * @return bool
     */
    public function save(ModuleModel $module): bool
    {
        $fields = $module->getArray();

        if ($this->getModuleByKey($module->getKey())) {
            $this->db()->update('modules')
                ->where(['key' => $module->getKey()])
                ->values($fields)
                ->execute();
        } else {
            $this->db()->insert('modules')
                ->values($fields)
                ->execute();
        }

        if ($this->getModuleByKey($module->getKey())) {
            $infosMapper = new Infos();
            $infosMapper->saveModulesFolderRights($module->getKey(), $module->getFolderRights())
                ->saveModulesPHPExtensions($module->getKey(), $module->getPHPExtension());

            foreach ($module->getContent() as $locale => $value) {
                if ($this->getModulesByKey($module->getKey(), $locale)) {
                    $this->db()->update('modules_content')
                        ->where(['key' => $module->getKey(), 'locale' => $locale])
                        ->values([
                            'key' => $module->getKey(),
                            'locale' => $locale,
                            'name' => $value['name'],
                            'description' => $value['description']
                        ])
                        ->execute();
                } else {
                    $this->db()->insert('modules_content')
                        ->values([
                            'key' => $module->getKey(),
                            'locale' => $locale,
                            'name' => $value['name'],
                            'description' => $value['description']
                        ])
                        ->execute();
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Deletes a given module with the given key.
     *
     * @param string $key
     */
    public function delete(string $key)
    {
        $menuMapper = new MenuMapper();
        $menuMapper->deleteItemsByModuleKey($key);
        $this->db()->delete('modules')
            ->where(['key' => $key])
            ->execute();
        // Rows in modules_boxes_content, modules_content, modules_folderrights and modules_php_extensions are being deleted due to FKCs.
    }

    /**
     * @param string $key
     * @param array $dependencies
     * @return array
     * @since 2.2.8
     */
    public function checkOthersDependencies(string $key, array $dependencies): array
    {
        $dependencyCheck = [];
        if (count($dependencies[$key])) {
            $dependencyCheck = $dependencies[$key];
            unset($dependencyCheck['version']);
        }
        return $dependencyCheck;
    }

    /**
     * @param string $modulKey
     * @param array|null $dependencies
     * @return bool
     * @since 2.2.8
     */
    public function checkOthersDependenciesVersion(string $modulKey, array $dependencies): bool
    {
        $dependencyCheck = true;
        foreach ($dependencies[$modulKey] as $key => $version) {
            if ($key != 'version') {
                $modulVersion = '';
                if ($dependencies[$key]) {
                    $modul = $this->getModuleByKey($key);
                    if ($modul) {
                        $modulVersion = $dependencies[$key]['version'];
                    }
                }

                $parsed = explode(',', $version);
                if (!empty($modulVersion) && !version_compare($modulVersion, $parsed[1], $parsed[0])) {
                    $dependencyCheck = false;
                }
            }
        }

        return $dependencyCheck;
    }
}
