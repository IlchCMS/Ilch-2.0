<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Mappers;

use Modules\Bugtracker\Models\Status as StatusModel;

use Ilch\Date;

class Status extends \Ilch\Mapper
{
    public function getAllStatus()
    {
        $link = $this->db()->getLink();

        $query = "SELECT * FROM [prefix]_bugtracker_status";
        $query = $this->db()->getSqlWithPrefix($query);
        $stmt = $link->prepare($query);
        $stmt->execute();

        $res = $stmt->get_result();

        $i = 0;
        $status = array();

        while($row = mysqli_fetch_assoc($res))
        {
            $id = $row['id'];
            $name = $row['name'];
            $cssClass = $row['css_class'];

            $status[$i] = new StatusModel($id, $name, $cssClass);
            $i++;
        }

        return $status;
    }
}
