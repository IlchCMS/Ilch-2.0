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
            ->fields(['m.key', 'm.system', 'm.version', 'm.link', 'm.icon_small', 'm.author'])
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
     */
    public function getModulesNotInstalled()
    {
        foreach (glob(APPLICATION_PATH.'/modules/*') as $modulePath) {
            $moduleModel = new ModuleModel();
            $moduleModel->setKey(basename($modulePath));
            $modulesDir[] = $moduleModel->getKey();
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
            $configClass = '\\Modules\\'.ucfirst($module).'\\Config\\config';
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
     * Gets an array of keys of the installed modules.
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
            $modulesRows[$moduleNotInstalled->getKey()] = array('key' => $moduleNotInstalled->getKey(), 'version' => $moduleNotInstalled->getVersion());
        }

        if (empty($modulesRows)) {
            return [];
        }

        return $modulesRows;
    }

    /**
     * Inserts a module model in the database.
     *
     * @param ModuleModel $module
     *
     * @return int
     */
    public function save(ModuleModel $module)
    {
        $moduleId = $this->db()->insert('modules')
            ->values([
                'key' => $module->getKey(),
                'system' => (int) $module->getSystemModule(),
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

        return (int) $moduleId;
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
    }
}
