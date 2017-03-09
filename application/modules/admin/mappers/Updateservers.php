<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Updateserver as UpdateserverModel;

class Updateservers extends \Ilch\Mapper
{
    /**
     * Get all updateservers.
     *
     * @return UpdateserverModel[]|[]
     */
    public function getUpdateservers()
    {
        $array = $this->db()->select('*')
                ->from('admin_updateservers')
                ->execute()
                ->fetchRows();

        if (empty($array)) {
            return $array;
        }

        $updateservers = [];
        foreach ($array as $entries) {
            $updateserverModel = new UpdateserverModel();
            $updateserverModel->setId($entries['id']);
            $updateserverModel->setURL($entries['url']);
            $updateserverModel->setOperator($entries['operator']);
            $updateserverModel->setCountry($entries['country']);
            $updateservers[] = $updateserverModel;
        }

        return $updateservers;
    }
}
