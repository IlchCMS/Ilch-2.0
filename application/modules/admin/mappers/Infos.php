<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Infos as InfosModel;

class Infos extends \Ilch\Mapper
{
    /**
     * Gets all module folder rights.
     *
     * @param array $where
     * @return InfosModel[]|null
     */
    public function getModulesFolderRights(array $where = []): ?array
    {
        $moduleArray = $this->db()->select('*')
            ->from('modules_folderrights')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($moduleArray)) {
            return null;
        }

        $module = [];
        foreach ($moduleArray as $entries) {
            $moduleModel = new InfosModel();
            $moduleModel->setKey($entries['key']);
            $moduleModel->setFolder($entries['folder']);
            $module[] = $moduleModel;
        }

        return $module;
    }

    /**
     * Gets the module folder rights for the given key.
     *
     * @param string $key
     * @return InfosModel[]|null
     * @since 2.2.8
     */
    public function getModulesFolderRightByKey(string $key): ?array
    {
        return $this->getModulesFolderRights(['key' => $key]);
    }

    /**
     * Saves the module folder rights for the given key.
     * This can be entered with an entry in the module configuration file with 'folderRights'.
     *
     * @param string $key
     * @param array $folders
     * @return Infos
     * @since 2.2.8
     */
    public function saveModulesFolderRights(string $key, array $folders): Infos
    {
        $existing = $this->getModulesFolderRightByKey($key);
        if (!empty($existing)) {
            $this->db()->delete('modules_folderrights')->where(['key' => $key])->execute();
        }

        foreach ($folders as $folder => $state) {
            $this->db()->insert('modules_folderrights')
                ->values([
                    'key' => $key,
                    'folder' => $folder,
                ])
                ->execute();
        }
        return $this;
    }

    /**
     * Gets all module PHP extensions.
     *
     * @param array $where
     * @return InfosModel[]|null
     */
    public function getModulesPHPExtensions(array $where = []): ?array
    {
        $moduleArray = $this->db()->select('*')
            ->from('modules_php_extensions')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($moduleArray)) {
            return null;
        }

        $module = [];
        foreach ($moduleArray as $entries) {
            $moduleModel = new InfosModel();
            $moduleModel->setKey($entries['key']);
            $moduleModel->setExtension($entries['extension']);
            $module[] = $moduleModel;
        }

        return $module;
    }

    /**
     * Gets the module PHP extensions for the given key.
     *
     * @param string $key
     * @return InfosModel[]|null
     * @since 2.2.8
     */
    public function getModulesPHPExtensionsByKey(string $key): ?array
    {
        return $this->getModulesPHPExtensions(['key' => $key]);
    }

    /**
     * Saves the module PHP extensions for the given key.
     * This can be entered with an entry in the module configuration file with 'phpExtensions'.
     *
     * @param string $key
     * @param array $extensions
     * @return Infos
     * @since 2.2.8
     */
    public function saveModulesPHPExtensions(string $key, array $extensions): Infos
    {
        $existing = $this->getModulesPHPExtensionsByKey($key);
        if (!empty($existing)) {
            $this->db()->delete('modules_php_extensions')->where(['key' => $key])->execute();
        }

        foreach ($extensions as $extension => $state) {
            $this->db()->insert('modules_php_extensions')
                ->values([
                    'key' => $key,
                    'extension' => $extension,
                ])
                ->execute();
        }
        return $this;
    }
}
