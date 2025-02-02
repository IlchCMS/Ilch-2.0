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
     * Gets all modules folder rights.
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
     * Get modules folder rights by given key.
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
     * save modules folder rights by given key.
     * This can be entered with an entry in the module configuration file with 'folderRights'.
     *
     * @param string $key
     * @param array $folders
     * @return $this
     * @since 2.2.8
     */
    public function saveModulesFolderRights(string $key, array $folders): Infos
    {
        $moduls = $this->getModulesPHPExtensionsByKey($key);
        if (!empty($moduls)) {
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
     * Gets all modules php extensions.
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
     * Get modules php extensions by given key.
     *
     * @param string $key
     * @return InfosModel[]|null
     * @since 2.2.8
     */
    public function getModulesPHPExtensionsByKey(string $key): ?array
    {
        return $this->getModulesFolderRights(['key' => $key]);
    }

    /**
     * save modules php extensions by given key.
     * This can be entered with an entry in the module configuration file with 'phpExtensions'.
     *
     * @param string $key
     * @param array $extensions
     * @return $this
     * @since 2.2.8
     */
    public function saveModulesPHPExtensions(string $key, array $extensions): Infos
    {
        $moduls = $this->getModulesPHPExtensionsByKey($key);
        if (!empty($moduls)) {
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
