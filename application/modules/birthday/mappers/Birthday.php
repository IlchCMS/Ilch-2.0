<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Birthday\Mappers;

class Birthday extends \Ilch\Mapper
{
    /**
     * @return \Modules\User\Mappers\User[]
     */
    public function getBirthdayUserList($limit = null)
    {
        $userMapper = new \Modules\User\Mappers\User();

        if ($limit != '') {
            $sql = 'SELECT *
                    FROM `[prefix]_users`
                    WHERE DAY(birthday) = DAY(CURDATE()) AND MONTH(birthday) = MONTH(CURDATE())
                    LIMIT '.$limit;
        }  else {
            $sql = 'SELECT *
                    FROM `[prefix]_users`
                    WHERE DAY(birthday) = DAY(CURDATE()) AND MONTH(birthday) = MONTH(CURDATE())';
        }
        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $users = array();

        foreach ($rows as $row) {
            $users[] = $userMapper->getUserById($row['id']);
        }

        return $users;
    }
}
