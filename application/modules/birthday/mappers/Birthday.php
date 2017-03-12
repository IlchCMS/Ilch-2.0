<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Birthday\Mappers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;

class Birthday extends \Ilch\Mapper
{
    /**
     * @return UserMapper[]
     */
    public function getBirthdayUserList($limit = null)
    {
        $userMapper = new UserMapper();

        if ($limit != '') {
            $sql = 'SELECT *
                    FROM `[prefix]_users`
                    WHERE DAY(birthday) = DAY(CURDATE()) AND MONTH(birthday) = MONTH(CURDATE())
                    LIMIT '.$limit;
        } else {
            $sql = 'SELECT *
                    FROM `[prefix]_users`
                    WHERE DAY(birthday) = DAY(CURDATE()) AND MONTH(birthday) = MONTH(CURDATE())';
        }
        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $users = [];
        foreach ($rows as $row) {
            $users[] = $userMapper->getUserById($row['id']);
        }

        return $users;
    }

    /**
     * Gets the Users by start and end.
     *
     * @param integer $start
     * @param integer $end
     * @return UserModel|null
     */
    public function getEntriesForJson($start, $end)
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            $sql = sprintf("SELECT * FROM `[prefix]_users` WHERE `birthday` >= '%s' AND `birthday` <= '%s' ORDER BY `birthday` ASC;", $start, $end);
        } else {
            return null;
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new UserModel();
            $entryModel->setId($entries['id']);
            $entryModel->setName($entries['name']);
            $entryModel->setBirthday($entries['birthday']);
            $entry[] = $entryModel;
        }

        return $entry;
    }
}
