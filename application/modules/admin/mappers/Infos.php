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
     * @return array|\Modules\Admin\Models\Infos[]
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

        $module = array();

        foreach ($moduleArray as $entries) {
            $moduleModel = new InfosModel();
            $moduleModel->setKey($entries['key']);
            $moduleModel->setFolder($entries['folder']);
            $module[] = $moduleModel;
        }

        return $module;
    }
}
