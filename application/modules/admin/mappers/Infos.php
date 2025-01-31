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
     * @return InfosModel[]
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
     * @param string $key
     * @return InfosModel|null
     * @since 2.2.8
     */
    public function getModulesFolderRightByKey(string $key): ?InfosModel
    {
        $entries = $this->getModulesFolderRights(['key' => $key]);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * @param string $key
     * @param string $folder
     * @return $this
     * @since 2.2.8
     */
    public function saveModulesFolderRight(string $key, string $folder): Infos
    {
        $modul = $this->getModulesFolderRightByKey($key);
        if ($modul && !empty($modul->getFolder())) {
            $this->db()->delete('modules_folderrights')->where(['key' => $key])->execute();
        }

        if (!empty($folder)) {
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
     * @return InfosModel[]
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
     * @param string $key
     * @return InfosModel[]
     * @since 2.2.8
     */
    public function getModulesPHPExtensionsByKey(string $key): array
    {
        return $this->getModulesFolderRights(['key' => $key]);
    }

    /**
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

        foreach ($extensions as $extension) {
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
