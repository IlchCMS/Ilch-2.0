<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Infos as InfosModel;

class Infos extends \Ilch\Mapper
{
    /**
     * Gets all modules folder rights.
     *
     * @return InfosModel[]|array
     */
    public function getModulesFolderRights()
    {
        $moduleArray = $this->db()->select('*')
                ->from('modules_folderrights')
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
     * Gets all modules php extensions.
     *
     * @return InfosModel[]|array
     */
    public function getModulesPHPExtensions()
    {
        $moduleArray = $this->db()->select('*')
                ->from('modules_php_extensions')
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
}
