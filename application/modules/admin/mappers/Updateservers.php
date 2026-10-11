<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Updateserver as UpdateserverModel;

class Updateservers extends \Ilch\Mapper
{
    /**
     * Gets all update servers.
     *
     * @return UpdateserverModel[]
     */
    public function getUpdateservers(): array
    {
        $array = $this->db()->select('*')
            ->from('admin_updateservers')
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return [];
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
